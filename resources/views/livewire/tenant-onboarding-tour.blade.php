<div
    x-data="{ minimized: $persist(false).as('onboarding_minimized') }"
    class="fixed bottom-6 right-6 z-[999]"
>
@if ($this->visible)

    {{-- Minimized FAB --}}
    <div x-show="minimized" x-cloak>
        <button
            @click="minimized = false"
            class="flex items-center gap-2 px-4 py-3 bg-primary-600 text-white rounded-2xl shadow-lg hover:bg-primary-700 transition-all hover:scale-105"
        >
            <x-heroicon-o-map class="w-4 h-4 flex-none" />
            <span class="text-sm font-semibold">{{ __('klinica.onboarding.step_of', ['step' => $step, 'total' => $totalSteps]) }}</span>
            <x-heroicon-o-chevron-up class="w-3.5 h-3.5 flex-none opacity-70" />
        </button>
    </div>

    {{-- Expanded panel --}}
    <div
        x-show="!minimized"
        x-cloak
        class="w-[380px] rounded-2xl bg-white dark:bg-gray-900 shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden"
    >
        {{-- Progress bar --}}
        <div class="h-1 bg-gray-100 dark:bg-gray-800">
            <div
                class="h-full bg-primary-500 transition-all duration-500"
                style="width: {{ round(($step / $totalSteps) * 100) }}%"
            ></div>
        </div>

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 pt-4 pb-2">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                {{ __('klinica.onboarding.step_of', ['step' => $step, 'total' => $totalSteps]) }}
            </span>
            <div class="flex items-center gap-1">
                <button
                    @click="minimized = true"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    title="Minimise"
                >
                    <x-heroicon-o-minus class="w-4 h-4" />
                </button>
                <button
                    wire:click="skip"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    title="{{ __('klinica.onboarding.skip') }}"
                >
                    <x-heroicon-o-x-mark class="w-4 h-4" />
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-5 pb-5">
            {{-- Icon + title --}}
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center flex-none">
                    <x-dynamic-component
                        :component="$currentStep['icon']"
                        class="w-5 h-5 text-primary-500"
                    />
                </div>
                <h2 class="text-sm font-bold text-gray-900 dark:text-white leading-snug">
                    {{ $currentStep['title'] }}
                </h2>
            </div>

            {{-- Description --}}
            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-5">
                {{ $currentStep['description'] }}
            </p>

            {{-- Step dots --}}
            <div class="flex justify-center gap-1.5 mb-5">
                @for ($i = 1; $i <= $totalSteps; $i++)
                    <button
                        wire:click="$set('step', {{ $i }})"
                        @class([
                            'rounded-full transition-all duration-200',
                            'w-2 h-2 bg-primary-500' => $i === $step,
                            'w-1.5 h-1.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300' => $i !== $step,
                        ])
                    ></button>
                @endfor
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-2">
                @if ($step > 1)
                    <button
                        wire:click="prev"
                        class="flex-none px-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                    >
                        {{ __('klinica.onboarding.back') }}
                    </button>
                @endif

                <div class="flex flex-1 gap-2">
                    @if ($currentStep['action_url'])
                        <a
                            href="{{ $currentStep['action_url'] }}"
                            class="flex-1 text-center px-3 py-2 text-xs font-semibold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/30 rounded-xl hover:bg-primary-100 transition-colors border border-primary-200 dark:border-primary-800"
                        >
                            {{ $currentStep['action_label'] }} →
                        </a>
                    @endif

                    @if ($step < $totalSteps)
                        <button
                            wire:click="next"
                            class="flex-1 px-3 py-2 text-xs font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors shadow-sm"
                        >
                            {{ __('klinica.onboarding.next') }}
                        </button>
                    @else
                        <button
                            wire:click="complete"
                            class="flex-1 px-3 py-2 text-xs font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors shadow-sm"
                        >
                            {{ __('klinica.onboarding.get_started') }}
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endif
</div>
