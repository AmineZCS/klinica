<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Central\CreateTenantAction;
use App\Enums\TenantStatus;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Stancl\Tenancy\Database\Models\Domain;

class TenantRegistrationController extends Controller
{
    public function __construct(private readonly CreateTenantAction $createTenant) {}

    private const RESERVED_SUBDOMAINS = [
        'www', 'mail', 'smtp', 'imap', 'pop', 'ftp', 'ns1', 'ns2', 'ns3',
        'admin', 'api', 'app', 'dev', 'staging', 'beta', 'test', 'demo',
        'klinica', 'support', 'help', 'status', 'blog', 'cdn', 'assets',
        'static', 'media', 'files', 'docs', 'auth', 'login', 'register',
        'dashboard', 'panel', 'billing', 'webhooks',
    ];

    public function create(): View
    {
        return view('registration.create');
    }

    public function checkSubdomain(Request $request): JsonResponse
    {
        $subdomain = Str::lower(trim((string) $request->query('subdomain', '')));

        if (! preg_match('/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/', $subdomain) || strlen($subdomain) > 63) {
            return response()->json(['available' => false, 'reason' => 'invalid']);
        }

        if (in_array($subdomain, self::RESERVED_SUBDOMAINS, true)) {
            return response()->json(['available' => false, 'reason' => 'reserved']);
        }

        $taken = Tenant::where('id', $subdomain)->exists()
            || Domain::where('domain', $subdomain)->exists();

        return response()->json(['available' => ! $taken]);
    }

    public function store(Request $request): RedirectResponse
    {
        $subdomain = Str::lower(trim((string) $request->input('subdomain', '')));

        $validated = $request->merge(['subdomain' => $subdomain])->validate([
            'plan' => ['required', 'string', 'in:starter,pro'],
            'clinic_name' => ['required', 'string', 'max:255'],
            'subdomain' => [
                'required',
                'string',
                'max:63',
                'regex:/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/',
                Rule::notIn(self::RESERVED_SUBDOMAINS),
                'unique:tenants,id',
                'unique:domains,domain',
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'locale' => ['required', 'in:en,fr,de,es'],
            'terms_accepted' => ['accepted'],
        ], [
            'subdomain.regex' => 'Subdomain may only contain lowercase letters, numbers, and hyphens, and cannot start or end with a hyphen.',
            'subdomain.not_in' => 'That subdomain is reserved. Please choose a different one.',
            'subdomain.unique' => 'That subdomain is already taken. Please choose a different one.',
            'terms_accepted.accepted' => 'You must accept the Terms of Service, Privacy Policy and Data Processing Agreement to continue.',
        ]);

        $tenant = $this->createTenant->execute([
            'name' => $validated['clinic_name'],
            'subdomain' => $validated['subdomain'],
            'admin_name' => $validated['name'],
            'admin_email' => $validated['email'],
            'admin_password' => $validated['password'],
            'plan_tier' => $validated['plan'],
            'locale' => $validated['locale'],
        ]);

        // Provisioning runs on the queue; send the user to a page that polls for
        // readiness and then forwards to their clinic login.
        return redirect()->route('registering', ['tenant' => $tenant->id]);
    }

    /**
     * The "setting up your clinic" page shown while provisioning runs.
     */
    public function provisioning(Tenant $tenant): View
    {
        return view('registration.provisioning', ['tenant' => $tenant]);
    }

    /**
     * Polled by the provisioning page to learn when the tenant is ready.
     */
    public function status(Tenant $tenant): JsonResponse
    {
        return response()->json([
            'status' => $tenant->status?->value ?? TenantStatus::Provisioning->value,
            'login_url' => $tenant->isReady() ? $tenant->dashboardLoginUrl() : null,
        ]);
    }
}
