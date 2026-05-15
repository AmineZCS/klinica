<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Central\CreateTenantAction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantRegistrationController extends Controller
{
    public function __construct(private readonly CreateTenantAction $createTenant) {}

    public function create(): View
    {
        return view('registration.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'string', 'in:starter,pro'],
            'clinic_name' => ['required', 'string', 'max:255'],
            'subdomain' => ['required', 'string', 'max:63', 'alpha_dash', 'unique:domains,domain'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $tenant = $this->createTenant->execute([
            'name' => $validated['clinic_name'],
            'subdomain' => $validated['subdomain'],
            'admin_name' => $validated['name'],
            'admin_email' => $validated['email'],
            'admin_password' => $validated['password'],
            'plan_tier' => $validated['plan'],
        ]);

        return redirect()->away($tenant->dashboardLoginUrl());
    }
}
