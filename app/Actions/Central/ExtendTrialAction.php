<?php

declare(strict_types=1);

namespace App\Actions\Central;

use App\Models\Tenant;
use Carbon\Carbon;

class ExtendTrialAction
{
    public function execute(Tenant $tenant, int $days): Tenant
    {
        $base = $tenant->trial_ends_at && $tenant->trial_ends_at->isFuture()
            ? $tenant->trial_ends_at
            : Carbon::now();

        $tenant->update([
            'trial_ends_at' => $base->copy()->addDays($days),
            'trial_reminder_sent_at' => null,
        ]);

        return $tenant->refresh();
    }
}
