<?php

declare(strict_types=1);

namespace App\Actions\Central;

use App\Models\Tenant;
use Carbon\Carbon;

class ApplyDiscountCodeAction
{
    /** Valid discount codes and the number of free days they grant. */
    private const array CODES = [
        'LAUNCH30' => 30,
        'BETA90' => 90,
    ];

    public function execute(Tenant $tenant, string $code): bool
    {
        $normalised = strtoupper(trim($code));

        if (! array_key_exists($normalised, self::CODES)) {
            return false;
        }

        $days = self::CODES[$normalised];

        $tenant->update([
            'discount_code' => $normalised,
            'trial_ends_at' => Carbon::now()->addDays($days),
        ]);

        return true;
    }
}
