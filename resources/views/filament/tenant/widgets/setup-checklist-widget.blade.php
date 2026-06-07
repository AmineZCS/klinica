@if (!$dismissed)
<div class="fi-wi-stats-overview rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 p-6 shadow-sm">

    {{-- Header --}}
    <div class="flex items-start justify-between gap-4 mb-5">
        <div>
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                {{ __('klinica.onboarding.checklist_title') }}
            </h2>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                {{ __('klinica.onboarding.checklist_subtitle') }}
            </p>
        </div>

        {{-- Progress badge --}}
        <span @class([
            'flex-none inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold',
            'bg-success-50 text-success-700 dark:bg-success-950/40 dark:text-success-400' => $allDone,
            'bg-primary-50 text-primary-700 dark:bg-primary-950/40 dark:text-primary-400' => !$allDone,
        ])>
            @if ($allDone)
                <x-heroicon-o-check-circle class="w-3.5 h-3.5" />
            @else
                <x-heroicon-o-clock class="w-3.5 h-3.5" />
            @endif
            {{ $completedCount }} / {{ $totalSteps }}
        </span>
    </div>

    {{-- Progress bar --}}
    <div class="mb-5 h-1.5 rounded-full bg-gray-100 dark:bg-gray-800">
        <div
            class="h-full rounded-full bg-primary-500 transition-all duration-500"
            style="width: {{ round(($completedCount / $totalSteps) * 100) }}%"
        ></div>
    </div>

    @if ($allDone)
        {{-- All done state --}}
        <div class="flex items-center justify-between rounded-xl bg-success-50 dark:bg-success-950/30 border border-success-200 dark:border-success-800 px-5 py-4">
            <div class="flex items-center gap-3">
                <x-heroicon-o-check-circle class="w-6 h-6 text-success-500 flex-none" />
                <p class="text-sm font-medium text-success-700 dark:text-success-300">
                    {{ __('klinica.onboarding.checklist_all_done') }}
                </p>
            </div>
            <div class="flex items-center gap-3 flex-none">
                <a
                    href="{{ $clinicUrl }}"
                    target="_blank"
                    rel="noopener"
                    class="text-sm font-semibold text-success-600 dark:text-success-400 hover:underline"
                >
                    {{ __('klinica.onboarding.checklist_view_page') }}
                </a>
                <button
                    wire:click="dismiss"
                    class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
                >
                    {{ __('klinica.onboarding.checklist_dismiss') }}
                </button>
            </div>
        </div>
    @else
        {{-- Steps --}}
        <div class="grid gap-3 sm:grid-cols-3">
            @foreach ($steps as $step)
                <a
                    href="{{ $step['url'] }}"
                    @class([
                        'group flex items-start gap-3 rounded-xl border p-4 transition-all',
                        'border-success-200 bg-success-50 dark:border-success-800 dark:bg-success-950/20' => $step['done'],
                        'border-gray-200 bg-gray-50 hover:border-primary-300 hover:bg-primary-50 dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-primary-700 dark:hover:bg-primary-950/20' => !$step['done'],
                    ])
                >
                    {{-- Checkbox icon --}}
                    <div @class([
                        'mt-0.5 flex h-5 w-5 flex-none items-center justify-center rounded-full',
                        'bg-success-500' => $step['done'],
                        'border-2 border-gray-300 dark:border-gray-600 group-hover:border-primary-400' => !$step['done'],
                    ])>
                        @if ($step['done'])
                            <x-heroicon-s-check class="w-3 h-3 text-white" />
                        @endif
                    </div>

                    {{-- Text --}}
                    <div class="min-w-0">
                        <p @class([
                            'text-sm font-medium leading-snug',
                            'text-success-700 dark:text-success-300 line-through' => $step['done'],
                            'text-gray-900 dark:text-white group-hover:text-primary-700 dark:group-hover:text-primary-300' => !$step['done'],
                        ])>
                            {{ $step['title'] }}
                        </p>
                        <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                            {{ $step['description'] }}
                        </p>
                    </div>

                    @if (!$step['done'])
                        <x-heroicon-o-arrow-right class="mt-0.5 w-4 h-4 flex-none text-gray-400 group-hover:text-primary-500 transition-colors ml-auto" />
                    @endif
                </a>
            @endforeach
        </div>

        {{-- Bottom row --}}
        <div class="mt-4 flex items-center justify-between">
            <a
                href="{{ $clinicUrl }}"
                target="_blank"
                rel="noopener"
                class="text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline"
            >
                {{ __('klinica.onboarding.checklist_view_page') }}
            </a>
            <button
                wire:click="dismiss"
                class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors"
            >
                {{ __('klinica.onboarding.checklist_dismiss') }}
            </button>
        </div>
    @endif
</div>
@endif
