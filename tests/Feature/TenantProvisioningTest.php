<?php

declare(strict_types=1);

use App\Enums\TenantPlan;
use App\Enums\TenantStatus;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    // Prevent the TenantCreated provisioning pipeline from actually creating a
    // tenant database during these tests; we only assert the central-side state.
    Queue::fake();
});

it('creates a provisioning tenant and redirects to the polling page', function (): void {
    $response = $this->post('/register', [
        'plan' => 'starter',
        'clinic_name' => 'Test Clinic',
        'subdomain' => 'testclinic',
        'name' => 'Dr Test',
        'email' => 'owner@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'locale' => 'en',
        'terms_accepted' => '1',
    ]);

    $response->assertRedirect(route('registering', ['tenant' => 'testclinic']));

    $tenant = Tenant::find('testclinic');

    expect($tenant)->not->toBeNull()
        ->and($tenant->status)->toBe(TenantStatus::Provisioning)
        ->and(Cache::get('tenant_setup:testclinic'))->toBeArray();
});

it('reports status and withholds the login url until the tenant is ready', function (): void {
    $tenant = Tenant::create([
        'id' => 'readyclinic',
        'name' => 'Ready Clinic',
        'status' => TenantStatus::Provisioning,
        'plan' => TenantPlan::Trial,
        'plan_tier' => 'starter',
    ]);

    $this->getJson('/registering/readyclinic/status')
        ->assertOk()
        ->assertJson(['status' => 'provisioning', 'login_url' => null]);

    $tenant->update(['status' => TenantStatus::Ready]);

    $this->getJson('/registering/readyclinic/status')
        ->assertOk()
        ->assertJsonPath('status', 'ready')
        ->assertJsonPath('login_url', fn (?string $url): bool => is_string($url) && str_contains($url, 'readyclinic'));
});
