@php
use App\Filament\Tenant\Pages\ClinicSettingsPage;

$themes = [
    'sage'       => ['primary' => '#7A9E8A', 'secondary' => '#A8C5B5', 'name' => __('klinica.settings.theme_sage')],
    'lavender'   => ['primary' => '#9B8DC4', 'secondary' => '#C8B8E0', 'name' => __('klinica.settings.theme_lavender')],
    'ocean'      => ['primary' => '#6BA8C4', 'secondary' => '#8ECFCA', 'name' => __('klinica.settings.theme_ocean')],
    'blush'      => ['primary' => '#C47B8E', 'secondary' => '#E8A8B8', 'name' => __('klinica.settings.theme_blush')],
    'peach'      => ['primary' => '#C49080', 'secondary' => '#E8C0A8', 'name' => __('klinica.settings.theme_peach')],
    'mint'       => ['primary' => '#6BAA96', 'secondary' => '#A4D4C4', 'name' => __('klinica.settings.theme_mint')],
    'periwinkle' => ['primary' => '#7B90C8', 'secondary' => '#A8C0E8', 'name' => __('klinica.settings.theme_periwinkle')],
    'mauve'      => ['primary' => '#B47AA0', 'secondary' => '#D8AAC8', 'name' => __('klinica.settings.theme_mauve')],
    'slate'      => ['primary' => '#7A8EA0', 'secondary' => '#9AB0C4', 'name' => __('klinica.settings.theme_slate')],
    'terracotta' => ['primary' => '#C47A60', 'secondary' => '#E8B89A', 'name' => __('klinica.settings.theme_terracotta')],
    'forest'     => ['primary' => '#7A9E78', 'secondary' => '#A8C4A4', 'name' => __('klinica.settings.theme_forest')],
    'dusk'       => ['primary' => '#9880C4', 'secondary' => '#C4B0D8', 'name' => __('klinica.settings.theme_dusk')],
];
@endphp

<div
    x-data="{
        selected: $wire.color_theme,
        pick(key) {
            this.selected = key;
            $wire.selectTheme(key);
        }
    }"
    x-init="$wire.$watch('color_theme', v => { selected = v; })"
    class="col-span-full"
>
    {{-- Label --}}
    <p class="text-sm font-medium text-gray-950 dark:text-white mb-3">
        {{ __('klinica.settings.field_color_theme') }}
    </p>

    {{-- Palette grid --}}
    <div class="grid grid-cols-4 gap-2.5 sm:grid-cols-6">
        @foreach ($themes as $key => $theme)
            <button
                type="button"
                x-on:click="pick('{{ $key }}')"
                title="{{ $theme['name'] }}"
                class="group relative rounded-xl overflow-hidden focus:outline-none transition-transform duration-150 hover:scale-105"
                :style="selected === '{{ $key }}'
                    ? 'box-shadow: 0 0 0 3px {{ $theme['primary'] }}, 0 0 0 5px white, 0 2px 10px rgba(0,0,0,0.15);'
                    : 'box-shadow: 0 0 0 1px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.06);'"
            >
                {{-- Color swatch: diagonal split --}}
                <div style="height: 54px; background: linear-gradient(135deg, {{ $theme['primary'] }} 50%, {{ $theme['secondary'] }} 50%); position: relative;">

                    {{-- Dot accents (decorative circles) --}}
                    <div style="
                        position: absolute; bottom: 6px; left: 8px;
                        width: 10px; height: 10px; border-radius: 50%;
                        background: {{ $theme['secondary'] }}; opacity: 0.85;
                        border: 1.5px solid rgba(255,255,255,0.6);
                    "></div>
                    <div style="
                        position: absolute; top: 6px; right: 8px;
                        width: 10px; height: 10px; border-radius: 50%;
                        background: {{ $theme['primary'] }}; opacity: 0.85;
                        border: 1.5px solid rgba(255,255,255,0.6);
                    "></div>

                    {{-- Selected checkmark --}}
                    <div
                        x-show="selected === '{{ $key }}'"
                        x-cloak
                        style="
                            position: absolute; inset: 0;
                            display: flex; align-items: center; justify-content: center;
                            background: rgba(0,0,0,0.18);
                        "
                    >
                        <div style="
                            background: white; border-radius: 50%;
                            width: 22px; height: 22px;
                            display: flex; align-items: center; justify-content: center;
                            box-shadow: 0 1px 4px rgba(0,0,0,0.2);
                        ">
                            <svg width="11" height="11" viewBox="0 0 11 11" fill="none">
                                <path d="M1.5 5.5L4.5 8.5L9.5 2.5" stroke="{{ $theme['primary'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Name --}}
                <div class="bg-white dark:bg-gray-800 py-1.5 px-1 text-center">
                    <span class="block text-[11px] font-medium text-gray-600 dark:text-gray-300 leading-none truncate">
                        {{ $theme['name'] }}
                    </span>
                </div>
            </button>
        @endforeach
    </div>

    {{-- Custom colors toggle --}}
    <button
        type="button"
        x-on:click="pick('custom')"
        class="mt-3 w-full flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-150 focus:outline-none"
        :style="selected === 'custom'
            ? 'background: #f0fdf4; color: #166534; box-shadow: 0 0 0 2px #7A9E8A;'
            : 'background: var(--color-gray-50, #f9fafb); color: var(--color-gray-500, #6b7280); box-shadow: 0 0 0 1px rgba(0,0,0,0.08);'"
    >
        {{-- Color wheel icon --}}
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 2C8.13 2 4.6 3.96 2.46 7"/>
            <path d="M2 12c0 1.85.5 3.58 1.38 5.06"/>
            <path d="M12 22c3.87 0 7.4-1.96 9.54-5"/>
            <path d="M22 12c0-1.85-.5-3.58-1.38-5.06"/>
        </svg>
        {{ __('klinica.settings.theme_custom') }}
        <div x-show="selected === 'custom'" x-cloak style="margin-left: auto; display: flex; align-items: center;">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M2 7L5.5 10.5L12 3.5" stroke="#166534" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </button>
</div>
