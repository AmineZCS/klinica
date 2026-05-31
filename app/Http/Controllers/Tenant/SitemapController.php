<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ClinicSetting;
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
        $lastMod = $settings?->updated_at ?? now();

        $base = rtrim(url('/'), '/');

        $sitemap = Sitemap::create()
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
            );

        return $sitemap->toResponse($request);
    }

    public function llms(): HttpResponse
    {
        $settings = ClinicSetting::current();
        $base = rtrim(url('/'), '/');

        $name = $settings?->clinic_name ?? (string) (tenancy()->tenant?->id ?? 'This clinic');
        $tagline = $settings?->tagline ?? null;
        $phone = $settings?->contact_phone ?? null;
        $email = $settings?->contact_email ?? null;
        $address = $settings?->contact_address ?? null;

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
