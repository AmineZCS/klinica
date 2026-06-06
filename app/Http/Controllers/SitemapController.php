<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends Controller
{
    public function index(Request $request): Response
    {
        $sitemap = Sitemap::create()
            ->add(
                Url::create(route('home'))
                    ->setPriority(1.0)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate(now())
            )
            ->add(
                Url::create(route('register'))
                    ->setPriority(0.9)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            )
            ->add(
                Url::create(route('privacy'))
                    ->setPriority(0.3)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            )
            ->add(
                Url::create(route('dpa'))
                    ->setPriority(0.2)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            )
            ->add(
                Url::create(route('refund-policy'))
                    ->setPriority(0.2)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            )
            ->add(
                Url::create(route('terms'))
                    ->setPriority(0.3)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_YEARLY)
            );

        return $sitemap->toResponse($request);
    }

    public function llms(): HttpResponse
    {
        $lines = [
            '# Klinica',
            '',
            '> Klinica is a SaaS clinic management platform. Each clinic gets its own subdomain with a public landing page and online appointment booking.',
            '',
            '## Pages',
            '',
            '- [Home](https://klinica.space/): Platform overview',
            '- [Register](https://klinica.space/register): Start a free trial — no credit card required',
            '- [Privacy Policy](https://klinica.space/privacy)',
            '- [Terms of Service](https://klinica.space/terms)',
            '',
            '## Platform',
            '',
            'Klinica provides independent medical clinics with:',
            '- A public landing page with online booking (accessible at clinic-name.klinica.space)',
            '- Patient management, appointment calendar, and employee roles',
            '- Automated email confirmations and reminders',
            '- Subscription plans: Trial (free) / Starter / Pro',
            '',
            '## For AI Agents',
            '',
            'Each tenant clinic has its own /llms.txt at their subdomain (e.g. https://my-clinic.klinica.space/llms.txt) describing their specific services, prices, and contact info.',
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=utf-8']);
    }
}
