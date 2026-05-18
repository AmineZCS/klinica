<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\TenantPlan;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Throwable;

class AdminAnalyticsService
{
    /**
     * @return array{
     *   totals: array<string, int>,
     *   labels: array<int, string>,
     *   signups_series: array<int, int>,
     *   visits_series: array<int, int>,
     *   tenants_usage: array<int, array<string, mixed>>
     * }
     */
    public function getDashboardAnalytics(): array
    {
        return Cache::remember(
            'admin:dashboard:analytics:v1',
            now()->addMinutes(5),
            fn (): array => $this->buildDashboardAnalytics(),
        );
    }

    /**
     * @return array{
     *   totals: array<string, int>,
     *   labels: array<int, string>,
     *   signups_series: array<int, int>,
     *   visits_series: array<int, int>,
     *   tenants_usage: array<int, array<string, mixed>>
     * }
     */
    private function buildDashboardAnalytics(): array
    {
        $today = Carbon::today();
        $windowStart = $today->copy()->subDays(29);

        /** @var array<string, int> $signupsByDate */
        $signupsByDate = collect(range(29, 0))
            ->mapWithKeys(fn (int $ago): array => [$today->copy()->subDays($ago)->toDateString() => 0])
            ->all();

        /** @var array<string, int> $visitsByDate */
        $visitsByDate = $signupsByDate;

        $totals = [
            'total_clinics' => Tenant::query()->count(),
            'active_subscriptions' => Tenant::query()
                ->whereIn('plan', [TenantPlan::Monthly->value, TenantPlan::Yearly->value])
                ->where('is_active', true)
                ->count(),
            'active_trials' => Tenant::query()
                ->where('plan', TenantPlan::Trial->value)
                ->where('trial_ends_at', '>', now())
                ->count(),
            'trial_signups_last_30_days' => 0,
            'landing_visits_last_30_days' => 0,
            'landing_visits_total' => 0,
            'total_bookings' => 0,
            'total_services' => 0,
            'total_employees' => 0,
        ];

        $tenantUsage = [];
        $tenants = Tenant::query()->orderBy('created_at', 'desc')->get();

        foreach ($tenants as $tenant) {
            $createdDate = $tenant->created_at?->toDateString();

            if ($createdDate !== null && array_key_exists($createdDate, $signupsByDate)) {
                $signupsByDate[$createdDate]++;
                $totals['trial_signups_last_30_days']++;
            }

            $usage = $this->collectTenantUsage($tenant, $windowStart);

            foreach ($usage['visits_by_date'] as $date => $count) {
                if (array_key_exists($date, $visitsByDate)) {
                    $visitsByDate[$date] += $count;
                }
            }

            $totals['landing_visits_last_30_days'] += $usage['visits_last_30_days'];
            $totals['landing_visits_total'] += $usage['visits_total'];
            $totals['total_bookings'] += $usage['bookings_count'];
            $totals['total_services'] += $usage['services_count'];
            $totals['total_employees'] += $usage['employees_count'];

            $tenantUsage[] = [
                'tenant_id' => (string) $tenant->id,
                'tenant_name' => (string) ($tenant->name ?? $tenant->id),
                'plan' => $tenant->plan?->label() ?? 'Unknown',
                'bookings_count' => $usage['bookings_count'],
                'services_count' => $usage['services_count'],
                'employees_count' => $usage['employees_count'],
                'visits_last_30_days' => $usage['visits_last_30_days'],
                'visits_total' => $usage['visits_total'],
                'created_at' => $tenant->created_at,
            ];
        }

        usort(
            $tenantUsage,
            fn (array $left, array $right): int => ($right['bookings_count'] <=> $left['bookings_count'])
                ?: ($right['visits_last_30_days'] <=> $left['visits_last_30_days'])
        );

        return [
            'totals' => $totals,
            'labels' => collect(array_keys($signupsByDate))
                ->map(fn (string $date): string => Carbon::parse($date)->format('M j'))
                ->values()
                ->all(),
            'signups_series' => array_values($signupsByDate),
            'visits_series' => array_values($visitsByDate),
            'tenants_usage' => $tenantUsage,
        ];
    }

    /**
     * @return array{
     *   bookings_count: int,
     *   services_count: int,
     *   employees_count: int,
     *   visits_total: int,
     *   visits_last_30_days: int,
     *   visits_by_date: array<string, int>
     * }
     */
    private function collectTenantUsage(Tenant $tenant, Carbon $windowStart): array
    {
        $usage = [
            'bookings_count' => 0,
            'services_count' => 0,
            'employees_count' => 0,
            'visits_total' => 0,
            'visits_last_30_days' => 0,
            'visits_by_date' => [],
        ];

        try {
            tenancy()->initialize($tenant);

            $usage['bookings_count'] = Booking::query()->count();
            $usage['services_count'] = Service::query()->count();
            $usage['employees_count'] = User::query()->count();
            $usage['visits_total'] = (int) DB::table('page_visits')->sum('count');

            /** @var array<string, int|string> $visitRows */
            $visitRows = DB::table('page_visits')
                ->where('date', '>=', $windowStart->toDateString())
                ->pluck('count', 'date')
                ->all();

            $usage['visits_by_date'] = collect($visitRows)
                ->map(fn (int|string $count): int => (int) $count)
                ->all();

            $usage['visits_last_30_days'] = array_sum($usage['visits_by_date']);
        } catch (Throwable $exception) {
            report($exception);
        } finally {
            try {
                tenancy()->end();
            } catch (Throwable) {
                // no-op
            }
        }

        return $usage;
    }
}
