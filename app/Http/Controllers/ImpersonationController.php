<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Stancl\Tenancy\Database\Models\ImpersonationToken;

class ImpersonationController extends Controller
{
    /**
     * Generates a short-lived token and redirects to the tenant's
     * login-as-admin URL so a super admin can enter the tenant panel.
     */
    public function __invoke(Tenant $tenant): RedirectResponse
    {
        tenancy()->initialize($tenant);
        $superAdmin = User::orderBy('id')->first();
        tenancy()->end();

        if (! $superAdmin) {
            abort(404, 'No super admin found for this tenant.');
        }

        $token = ImpersonationToken::create([
            'tenant_id' => $tenant->getTenantKey(),
            'user_id' => (string) $superAdmin->id,
            'redirect_url' => '/clinic',
            'auth_guard' => 'tenant',
        ]);

        return redirect()->away($tenant->tenantBaseUrl().'/impersonate/'.$token->token);
    }
}
