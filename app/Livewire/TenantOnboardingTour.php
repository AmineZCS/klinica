<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Filament\Tenant\Pages\ClinicSchedulePage;
use App\Filament\Tenant\Pages\ClinicSettingsPage;
use App\Filament\Tenant\Resources\EmployeeResource;
use App\Filament\Tenant\Resources\ServiceResource;
use App\Models\ClinicSetting;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TenantOnboardingTour extends Component
{
    public bool $visible = true;

    public int $step = 1;

    public int $totalSteps = 6;

    public function mount(): void
    {
        $this->step = session('onboarding_step', 1);

        if (session('onboarding_dismissed', false)) {
            $this->visible = false;
        }
    }

    /** @return array<int, array{title: string, description: string, icon: string, action_label: string|null, action_url: string|null}> */
    public function steps(): array
    {
        return [
            1 => [
                'title' => __('klinica.onboarding.step_1_title'),
                'description' => __('klinica.onboarding.step_1_desc'),
                'icon' => 'heroicon-o-sparkles',
                'action_label' => null,
                'action_url' => null,
            ],
            2 => [
                'title' => __('klinica.onboarding.step_2_title'),
                'description' => __('klinica.onboarding.step_2_desc'),
                'icon' => 'heroicon-o-clock',
                'action_label' => __('klinica.onboarding.step_2_action'),
                'action_url' => ClinicSchedulePage::getUrl(),
            ],
            3 => [
                'title' => __('klinica.onboarding.step_3_title'),
                'description' => __('klinica.onboarding.step_3_desc'),
                'icon' => 'heroicon-o-paint-brush',
                'action_label' => __('klinica.onboarding.step_3_action'),
                'action_url' => ClinicSettingsPage::getUrl(),
            ],
            4 => [
                'title' => __('klinica.onboarding.step_4_title'),
                'description' => __('klinica.onboarding.step_4_desc'),
                'icon' => 'heroicon-o-queue-list',
                'action_label' => __('klinica.onboarding.step_4_action'),
                'action_url' => ServiceResource::getUrl('index'),
            ],
            5 => [
                'title' => __('klinica.onboarding.step_5_title'),
                'description' => __('klinica.onboarding.step_5_desc'),
                'icon' => 'heroicon-o-user-group',
                'action_label' => __('klinica.onboarding.step_5_action'),
                'action_url' => EmployeeResource::getUrl('index'),
            ],
            6 => [
                'title' => __('klinica.onboarding.step_6_title'),
                'description' => __('klinica.onboarding.step_6_desc'),
                'icon' => 'heroicon-o-check-circle',
                'action_label' => null,
                'action_url' => null,
            ],
        ];
    }

    public function next(): void
    {
        if ($this->step < $this->totalSteps) {
            $this->step++;
            session(['onboarding_step' => $this->step]);
        }
    }

    public function prev(): void
    {
        if ($this->step > 1) {
            $this->step--;
            session(['onboarding_step' => $this->step]);
        }
    }

    public function complete(): void
    {
        session()->forget('onboarding_step');
        ClinicSetting::current()->update(['onboarding_completed_at' => now()]);
        $this->visible = false;
    }

    public function skip(): void
    {
        session(['onboarding_dismissed' => true]);
        $this->visible = false;
    }

    public function render(): View
    {
        return view('livewire.tenant-onboarding-tour', [
            'steps' => $this->steps(),
            'currentStep' => $this->steps()[$this->step],
        ]);
    }
}
