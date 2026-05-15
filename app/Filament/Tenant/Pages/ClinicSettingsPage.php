<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Pages;

use App\Models\ClinicSetting;
use App\Models\Service;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
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
    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'filament.tenant.pages.clinic-settings-page';

    public static function canAccess(): bool
    {
        $user = auth('tenant')->user();

        return $user?->is_super_admin || ($user?->hasPermissionTo('view_clinic_settings', 'tenant') ?? false);
    }

    // ── Branding ──────────────────────────────────────────────────────────
    public ?string $clinic_name = null;

    public ?string $tagline = null;

    public ?string $hero_title = null;

    public ?string $hero_subtitle = null;

    public ?string $hero_cta_text = null;

    public ?string $primary_color = null;

    public ?string $secondary_color = null;

    /**
     * Temp logo value stored by FileUpload.
     * Must be untyped (or mixed) — Livewire 4 cannot serialize typed nullable
     * when the FileUpload component passes back a TemporaryUploadedFile or null.
     *
     * @var string|array<int,string>|null
     */
    public $logo_upload = null;

    // ── Contact ───────────────────────────────────────────────────────────
    public ?string $contact_phone = null;

    public ?string $contact_email = null;

    public ?string $contact_address = null;

    // ── Localisation ─────────────────────────────────────────────────────
    public bool $online_booking_enabled = true;

    public string $locale = 'en';

    public string $timezone = 'UTC';

    public string $currency = 'DA';

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
            'timezone',            'currency',        ]));

        $this->renderPreview();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(['default' => 1, 'xl' => 2])->schema([

                // ── LEFT COLUMN: all settings ────────────────────────────
                Grid::make(1)->schema([

                    Section::make('Branding & Hero')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('clinic_name')->required()->maxLength(255)->live(onBlur: true)->afterStateUpdated(fn () => $this->renderPreview())->columnSpanFull(),
                            TextInput::make('tagline')
                                ->label('Tagline / Sub-heading')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            TextInput::make('hero_title')
                                ->label('Hero headline')
                                ->placeholder('e.g. Your health, our priority')
                                ->maxLength(120)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->renderPreview())
                                ->columnSpanFull(),
                            TextInput::make('hero_subtitle')
                                ->label('Hero sub-text')
                                ->placeholder('e.g. Book an appointment in 60 seconds')
                                ->maxLength(200)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->renderPreview())
                                ->columnSpanFull(),
                            TextInput::make('hero_cta_text')
                                ->label('CTA button text')
                                ->placeholder('Book Now')
                                ->maxLength(40)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn () => $this->renderPreview()),
                            ColorPicker::make('primary_color')->live()->afterStateUpdated(fn () => $this->renderPreview()),
                            ColorPicker::make('secondary_color')->live()->afterStateUpdated(fn () => $this->renderPreview()),
                            FileUpload::make('logo_upload')
                                ->label('Logo (transparent PNG/SVG recommended)')
                                ->helperText('Displayed in the nav and footer of your landing page.')
                                ->image()
                                ->disk('public')
                                ->directory('logos')
                                ->imagePreviewHeight('72')
                                ->acceptedFileTypes(['image/png', 'image/svg+xml', 'image/webp'])
                                ->nullable()
                                ->columnSpanFull(),
                        ]),
                    ]),

                    Section::make('Contact')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('contact_phone')->tel(),
                            TextInput::make('contact_email')->email(),
                            TextInput::make('contact_address')->columnSpanFull(),
                        ]),
                    ]),

                    Section::make('Localisation & Booking')->schema([
                        Grid::make(2)->schema([
                            Select::make('locale')
                                ->options(['en' => 'English', 'fr' => 'Français', 'de' => 'Deutsch'])
                                ->required(),
                            Select::make('timezone')
                                ->options(\DateTimeZone::listIdentifiers())
                                ->searchable()
                                ->required(),
                            Select::make('currency')
                                ->label('Currency symbol')
                                ->options([
                                    'DA' => 'DA — Algerian Dinar',
                                    '€' => '€ — Euro',
                                    '$' => '$ — US Dollar',
                                    '£' => '£ — British Pound',
                                    'SAR' => 'SAR — Saudi Riyal',
                                    'MAD' => 'MAD — Moroccan Dirham',
                                    'TND' => 'TND — Tunisian Dinar',
                                    'AED' => 'AED — UAE Dirham',
                                ])
                                ->required(),
                            Toggle::make('online_booking_enabled')
                                ->label('Enable online booking')
                                ->columnSpanFull(),
                        ]),
                    ]),
                ]),

                // ── RIGHT COLUMN: live preview ───────────────────────────
                Section::make('Live Preview')
                    ->description('Updates as you change the fields on the left.')
                    ->schema([
                        View::make('filament.tenant.pages.partials.settings-preview'),
                    ]),
            ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
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
            'contact_email' => ['nullable', 'email'],
            'contact_address' => ['nullable', 'string'],
            'online_booking_enabled' => ['boolean'],
            'locale' => ['required', 'in:en,fr,de'],
            'timezone' => ['required', 'timezone'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        $settings = ClinicSetting::current();
        $settings->update($data);

        // Handle logo upload → store in media library
        if (! empty($this->logo_upload)) {
            $path = is_array($this->logo_upload)
                ? reset($this->logo_upload)
                : $this->logo_upload;

            if ($path && Storage::disk('public')->exists($path)) {
                $settings->clearMediaCollection('logo');
                $settings->addMediaFromDisk($path, 'public')
                    ->usingFileName(basename($path))
                    ->toMediaCollection('logo');

                // Reset the upload field so the FileUpload doesn't render a broken URL
                $this->logo_upload = null;
            }
        }

        Notification::make()
            ->title(__('klinica.settings.saved'))
            ->success()
            ->send();

        $this->renderPreview();
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
}
