<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Widgets;

use App\Filament\Tenant\Pages\ClinicSchedulePage;
use App\Filament\Tenant\Pages\ClinicSettingsPage;
use App\Filament\Tenant\Resources\ServiceResource;
use App\Models\ClinicSchedule;
use App\Models\ClinicSetting;
use App\Models\Service;
use Filament\Widgets\Widget;

class SetupChecklistWidget extends Widget
{
    protected string $view = 'filament.tenant.widgets.setup-checklist-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = -10;

    public bool $dismissed = false;

    public static function canView(): bool
    {
        try {
            return ClinicSetting::current()->onboarding_completed_at === null;
        } catch (\Throwable) {
            return false;
        }
    }

    public function dismiss(): void
    {
        ClinicSetting::current()->update(['onboarding_completed_at' => now()]);
        $this->dismissed = true;
    }

    /** @return array<string, mixed> */
    public function getViewData(): array
    {
        $settings = ClinicSetting::current();

        $hasSchedule = ClinicSchedule::where('is_open', true)->exists();
        $hasServices = Service::where('is_active', true)->exists();
        $hasLogo = $settings->getFirstMedia('logo') !== null;

        $completedCount = collect([$hasSchedule, $hasServices, $hasLogo])->filter()->count();

        return [
            'steps' => [
                [
                    'done' => $hasSchedule,
                    'title' => __('klinica.onboarding.checklist_schedule'),
                    'description' => __('klinica.onboarding.checklist_schedule_desc'),
                    'url' => ClinicSchedulePage::getUrl(),
                ],
                [
                    'done' => $hasServices,
                    'title' => __('klinica.onboarding.checklist_services'),
                    'description' => __('klinica.onboarding.checklist_services_desc'),
                    'url' => ServiceResource::getUrl('index'),
                ],
                [
                    'done' => $hasLogo,
                    'title' => __('klinica.onboarding.checklist_branding'),
                    'description' => __('klinica.onboarding.checklist_branding_desc'),
                    'url' => ClinicSettingsPage::getUrl(),
                ],
            ],
            'completedCount' => $completedCount,
            'totalSteps' => 3,
            'allDone' => $completedCount >= 3,
            'clinicUrl' => url('/'),
        ];
    }
}
