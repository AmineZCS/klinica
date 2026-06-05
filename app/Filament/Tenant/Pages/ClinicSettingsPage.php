<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Pages;

use App\Models\ClinicSetting;
use App\Models\Service;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class ClinicSettingsPage extends Page
{
    /** @var array<string, array{primary: string, secondary: string}> */
    public const COLOR_THEMES = [
        'sage' => ['primary' => '#7A9E8A', 'secondary' => '#A8C5B5'],
        'lavender' => ['primary' => '#9B8DC4', 'secondary' => '#C8B8E0'],
        'ocean' => ['primary' => '#6BA8C4', 'secondary' => '#8ECFCA'],
        'blush' => ['primary' => '#C47B8E', 'secondary' => '#E8A8B8'],
        'peach' => ['primary' => '#C49080', 'secondary' => '#E8C0A8'],
        'mint' => ['primary' => '#6BAA96', 'secondary' => '#A4D4C4'],
        'periwinkle' => ['primary' => '#7B90C8', 'secondary' => '#A8C0E8'],
        'mauve' => ['primary' => '#B47AA0', 'secondary' => '#D8AAC8'],
        'slate' => ['primary' => '#7A8EA0', 'secondary' => '#9AB0C4'],
        'terracotta' => ['primary' => '#C47A60', 'secondary' => '#E8B89A'],
        'forest' => ['primary' => '#7A9E78', 'secondary' => '#A8C4A4'],
        'dusk' => ['primary' => '#9880C4', 'secondary' => '#C4B0D8'],
    ];

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function getNavigationGroup(): ?string
    {
        return __('klinica.settings.nav_group');
    }

    public function getTitle(): string
    {
        return __('klinica.settings.nav_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('klinica.settings.nav_label');
    }

    protected string $view = 'filament.tenant.pages.clinic-settings-page';

    public static function canAccess(): bool
    {
        $user = auth('tenant')->user();

        if (! $user) {
            return false;
        }

        try {
            return (bool) $user->hasPermissionTo('view_clinic_settings', 'tenant');
        } catch (\Throwable) {
            return false;
        }
    }

    // ── Branding ──────────────────────────────────────────────────────────
    public ?string $clinic_name = null;

    public ?string $tagline = null;

    public ?string $hero_title = null;

    public ?string $hero_subtitle = null;

    public ?string $hero_cta_text = null;

    public string $color_theme = 'custom';

    public ?string $primary_color = null;

    public ?string $secondary_color = null;

    // ── Contact ───────────────────────────────────────────────────────────
    public ?string $contact_phone = null;

    /** @var array<int, array{number: string}> */
    public array $contact_phones = [];

    public ?string $contact_email = null;

    public ?string $contact_address = null;

    // ── Localisation ─────────────────────────────────────────────────────
    public bool $online_booking_enabled = true;

    public string $locale = 'en';

    public string $timezone = 'UTC';

    public string $currency = '€';

    /** Rendered HTML of the landing page for the live preview iframe. */
    public string $previewHtml = '';

    public function mount(): void
    {
        $settings = ClinicSetting::current();

        $this->fill($settings->only([
            'clinic_name',
            'tagline',
            'hero_title',
            'hero_subtitle',
            'hero_cta_text',
            'primary_color',
            'secondary_color',
            'contact_phone',
            'contact_email',
            'contact_address',
            'online_booking_enabled',
            'locale',
            'timezone',
            'currency',
        ]));

        $this->contact_phones = is_array($settings->contact_phones) ? $settings->contact_phones : [];

        $this->color_theme = $this->detectTheme();

        $this->renderPreview();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(['default' => 1, 'xl' => 2])->schema([

                // ── LEFT COLUMN: all settings ────────────────────────────
                Grid::make(1)->schema([

                    Section::make(__('klinica.settings.section_branding'))->schema([
                        Grid::make(2)->schema([
                            TextInput::make('clinic_name')->required()->maxLength(255)->live(onBlur: true)->afterStateUpdated(fn () => $this->renderPreview())->columnSpanFull(),
                            TextInput::make('tagline')
                                ->label(__('klinica.settings.field_tagline'))
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->renderPreview())
                                ->columnSpanFull(),
                            TextInput::make('hero_title')
                                ->label(__('klinica.settings.field_hero_title'))
                                ->placeholder('e.g. Your health, our priority')
                                ->maxLength(120)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->renderPreview())
                                ->columnSpanFull(),
                            TextInput::make('hero_subtitle')
                                ->label(__('klinica.settings.field_hero_subtitle'))
                                ->placeholder('e.g. Book an appointment in 60 seconds')
                                ->maxLength(200)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->renderPreview())
                                ->columnSpanFull(),
                            TextInput::make('hero_cta_text')
                                ->label(__('klinica.settings.field_hero_cta'))
                                ->placeholder('Book Now')
                                ->maxLength(40)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->renderPreview())
                                ->columnSpanFull(),

                            View::make('filament.tenant.pages.partials.color-theme-picker')
                                ->columnSpanFull(),

                            ColorPicker::make('primary_color')
                                ->live()
                                ->afterStateUpdated(fn () => $this->renderPreview())
                                ->visible(fn (): bool => $this->color_theme === 'custom'),
                            ColorPicker::make('secondary_color')
                                ->live()
                                ->afterStateUpdated(fn () => $this->renderPreview())
                                ->visible(fn (): bool => $this->color_theme === 'custom'),
                        ]),
                    ]),

                    Section::make(__('klinica.settings.section_contact'))->schema([
                        Grid::make(2)->schema([
                            TextInput::make('contact_phone')
                                ->label(__('klinica.settings.field_phone'))
                                ->tel()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->renderPreview()),
                            TextInput::make('contact_email')->email()->live(onBlur: true)->afterStateUpdated(fn () => $this->renderPreview()),
                            TextInput::make('contact_address')->columnSpanFull()->live(onBlur: true)->afterStateUpdated(fn () => $this->renderPreview()),
                            Repeater::make('contact_phones')
                                ->label(__('klinica.settings.field_additional_phones'))
                                ->schema([
                                    TextInput::make('number')
                                        ->label(__('klinica.settings.field_phone_number'))
                                        ->tel()
                                        ->required()
                                        ->maxLength(50),
                                ])
                                ->addActionLabel(__('klinica.settings.add_phone'))
                                ->defaultItems(0)
                                ->live()
                                ->afterStateUpdated(fn () => $this->renderPreview())
                                ->columnSpanFull(),
                        ]),
                    ]),

                    Section::make(__('klinica.settings.section_localisation'))->schema([
                        Grid::make(2)->schema([
                            Select::make('locale')
                                ->options([
                                    'en' => __('klinica.settings.locale_en'),
                                    'fr' => __('klinica.settings.locale_fr'),
                                    'de' => __('klinica.settings.locale_de'),
                                    'es' => __('klinica.settings.locale_es'),
                                ])
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn () => $this->renderPreview()),
                            Select::make('timezone')
                                ->options(array_combine(\DateTimeZone::listIdentifiers(), \DateTimeZone::listIdentifiers()))
                                ->searchable()
                                ->required(),
                            Select::make('currency')
                                ->label(__('klinica.settings.field_currency'))
                                ->options([
                                    '€' => '€ — Euro',
                                    '$' => '$ — US Dollar',
                                    '£' => '£ — British Pound',
                                    'A$' => 'A$ — Australian Dollar',
                                    'C$' => 'C$ — Canadian Dollar',
                                    'CHF' => 'CHF — Swiss Franc',
                                ])
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn () => $this->renderPreview()),
                            Toggle::make('online_booking_enabled')
                                ->label(__('klinica.settings.field_online_booking'))
                                ->live()
                                ->afterStateUpdated(fn () => $this->renderPreview())
                                ->columnSpanFull(),
                        ]),
                    ]),
                ]),

                // ── RIGHT COLUMN: live preview ───────────────────────────
                Section::make(__('klinica.settings.section_preview'))
                    ->description(__('klinica.settings.section_preview_desc'))
                    ->schema([
                        View::make('filament.tenant.pages.partials.settings-preview'),
                    ]),
            ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('uploadLogo')
                ->label(__('klinica.settings.field_logo'))
                ->icon('heroicon-o-photo')
                ->color('gray')
                ->form([
                    FileUpload::make('logo')
                        ->label(__('klinica.settings.field_logo'))
                        ->helperText(__('klinica.settings.field_logo_helper'))
                        ->image()
                        ->disk('public')
                        ->directory('logos')
                        ->imagePreviewHeight('120')
                        ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/webp'])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $path = is_array($data['logo']) ? reset($data['logo']) : $data['logo'];

                    if ($path && Storage::disk('public')->exists($path)) {
                        $settings = ClinicSetting::current();
                        $settings->clearMediaCollection('logo');
                        $settings->addMediaFromDisk($path, 'public')
                            ->usingFileName(basename($path))
                            ->toMediaCollection('logo');

                        Notification::make()
                            ->title(__('klinica.settings.saved'))
                            ->success()
                            ->send();

                        $this->redirect(static::getUrl(), navigate: true);
                    }
                }),

            Action::make('save')
                ->label(__('klinica.settings.save_action'))
                ->action('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->validate([
            'clinic_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'hero_title' => ['nullable', 'string', 'max:120'],
            'hero_subtitle' => ['nullable', 'string', 'max:200'],
            'hero_cta_text' => ['nullable', 'string', 'max:40'],
            'primary_color' => ['nullable', 'string'],
            'secondary_color' => ['nullable', 'string'],
            'contact_phone' => ['nullable', 'string'],
            'contact_phones' => ['nullable', 'array'],
            'contact_phones.*.number' => ['required', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email'],
            'contact_address' => ['nullable', 'string'],
            'online_booking_enabled' => ['boolean'],
            'locale' => ['required', 'in:en,fr,de,es'],
            'timezone' => ['required', 'timezone'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        $settings = ClinicSetting::current();
        $settings->update($data);

        Notification::make()
            ->title(__('klinica.settings.saved'))
            ->success()
            ->send();

        $this->redirect(static::getUrl(), navigate: true);
    }

    public function renderPreview(): void
    {
        try {
            $settings = ClinicSetting::current();

            // Override in-memory attributes with the current (possibly unsaved) form values.
            $settings->fill([
                'clinic_name' => $this->clinic_name,
                'tagline' => $this->tagline,
                'hero_title' => $this->hero_title,
                'hero_subtitle' => $this->hero_subtitle,
                'hero_cta_text' => $this->hero_cta_text,
                'primary_color' => $this->primary_color,
                'secondary_color' => $this->secondary_color,
                'contact_phone' => $this->contact_phone,
                'contact_phones' => $this->contact_phones,
                'contact_email' => $this->contact_email,
                'contact_address' => $this->contact_address,
                'online_booking_enabled' => $this->online_booking_enabled,
            ]);

            $services = Service::query()
                ->where('is_active', true)
                ->take(6)
                ->get();

            $html = view('tenant.landing', [
                'settings' => $settings,
                'services' => $services,
            ])->render();

            // Disable interactive elements so preview is scroll-only (no navigation).
            $html = str_replace(
                '</head>',
                '<style>a,button,form{pointer-events:none!important;cursor:default!important}</style></head>',
                $html
            );

            $this->previewHtml = $html;
        } catch (\Throwable $e) {
            $this->previewHtml = '<p style="padding:20px;font-family:sans-serif;color:#CC4E4E;">Preview unavailable: '.e($e->getMessage()).'</p>';
        }
    }

    public function selectTheme(string $theme): void
    {
        $this->color_theme = $theme;

        if ($theme !== 'custom' && isset(self::COLOR_THEMES[$theme])) {
            $this->primary_color = self::COLOR_THEMES[$theme]['primary'];
            $this->secondary_color = self::COLOR_THEMES[$theme]['secondary'];
        }

        $this->renderPreview();
    }

    private function detectTheme(): string
    {
        foreach (self::COLOR_THEMES as $key => $colors) {
            if (strtolower($colors['primary']) === strtolower($this->primary_color ?? '')
                && strtolower($colors['secondary']) === strtolower($this->secondary_color ?? '')) {
                return $key;
            }
        }

        return 'custom';
    }
}
