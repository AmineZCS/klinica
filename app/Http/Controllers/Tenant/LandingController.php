<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ClinicSetting;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $settings = ClinicSetting::current();
        $services = Service::query()->where('is_active', true)->get();

        DB::table('page_visits')->upsert(
            [['date' => Carbon::today()->toDateString(), 'count' => 1, 'created_at' => now(), 'updated_at' => now()]],
            uniqueBy: ['date'],
            update: ['count' => DB::raw('count + 1'), 'updated_at' => now()],
        );

        return view('tenant.landing', compact('settings', 'services'));
    }
}
