<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
