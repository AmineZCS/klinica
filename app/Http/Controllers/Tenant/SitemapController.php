<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ClinicSetting;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    public function index(Request $request): Response
    {
        $settings = ClinicSetting::current();
        $hasServices = Service::where('is_active', true)->exists();

        if (! $settings?->clinic_name || ! $hasServices) {
            return Sitemap::create()->toResponse($request);
        }

        $lastMod = $settings->updated_at ?? now();
        $base = rtrim(url('/'), '/');

        return Sitemap::create()
            ->add(
                Url::create($base.'/')
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($lastMod)
            )
            ->add(
                Url::create($base.'/book')
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($lastMod)
            )
            ->toResponse($request);
    }

    public function llms(): HttpResponse
    {
        $settings = ClinicSetting::current();
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $base = rtrim(url('/'), '/');

        $name = $settings?->clinic_name ?? (string) (tenancy()->tenant?->id ?? 'This clinic');
        $tagline = $settings?->tagline ?? null;
        $phone = $settings?->contact_phone ?? null;
        $email = $settings?->contact_email ?? null;
        $address = $settings?->contact_address ?? null;
        $currency = $settings?->currency ?? '€';

        $description = $tagline
            ? "{$name} — {$tagline}"
            : "{$name} is a medical clinic offering online appointment booking via Klinica.";

        $lines = [
            "# {$name}",
            '',
            "> {$description}",
            '',
            "Book an appointment online at {$base}/book. No account needed.",
            '',
            '## Pages',
            '',
            "- [Home]({$base}/): Clinic overview and services",
            "- [Book an Appointment]({$base}/book): Online booking form",
        ];

        if ($services->isNotEmpty()) {
            $lines[] = '';
            $lines[] = '## Services';
            $lines[] = '';
            foreach ($services as $service) {
                $price = $service->price ? "{$currency}{$service->price}" : null;
                $duration = $service->duration ? "{$service->duration} min" : null;
                $meta = implode(', ', array_filter([$price, $duration]));
                $lines[] = $meta
                    ? "- **{$service->name}** ({$meta})".($service->description ? " — {$service->description}" : '')
                    : "- **{$service->name}**".($service->description ? " — {$service->description}" : '');
            }
        }

        if ($phone || $email || $address) {
            $lines[] = '';
            $lines[] = '## Contact';
            $lines[] = '';
            if ($phone) {
                $lines[] = "- Phone: {$phone}";
            }
            if ($email) {
                $lines[] = "- Email: {$email}";
            }
            if ($address) {
                $lines[] = "- Address: {$address}";
            }
        }

        $lines = array_merge($lines, [
            '',
            '## Platform',
            '',
            "{$name} runs on [Klinica](https://klinica.space) — a clinic management platform for independent medical practices.",
            '',
            '## Sitemap',
            '',
            "- [Sitemap]({$base}/sitemap.xml)",
        ]);

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }
}
