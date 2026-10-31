<?php

declare(strict_types=1);

beforeEach(function (): void {
    config(['tenancy.central_domains' => ['klinica.space', 'www.klinica.space']]);
});

it('301-redirects a www central host to the apex, preserving path and query', function (): void {
    $this->get('http://www.klinica.space/sitemap.xml?foo=bar')
        ->assertStatus(301)
        ->assertRedirect('http://klinica.space/sitemap.xml?foo=bar');
});

it('does not redirect the apex central host', function (): void {
    $this->get('http://klinica.space/')
        ->assertOk();
});
