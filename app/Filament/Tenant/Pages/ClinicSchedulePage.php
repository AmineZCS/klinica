<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Pages;

use App\Models\ClinicSchedule;
use App\Models\ClinicSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ClinicSchedulePage extends Page
{
    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    public function getTitle(): string
    {
        return __('klinica.schedule.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('klinica.schedule.nav_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('klinica.settings.nav_group');
    }

    public static function canAccess(): bool
    {
        $user = auth('tenant')->user();

        return $user?->is_super_admin || ($user?->hasPermissionTo('manage_schedule', 'tenant') ?? false);
    }

    protected string $view = 'filament.tenant.pages.clinic-schedule-page';

    // Sunday = 0 … Saturday = 6  (Carbon convention)
    public bool $sun_open = false;

    public string $sun_from = '09:00';

    public string $sun_to = '17:00';

    public bool $sun_has_lunch = false;

    public string $sun_lunch_from = '12:00';

    public string $sun_lunch_to = '13:00';

    public bool $mon_open = true;

    public string $mon_from = '09:00';

    public string $mon_to = '17:00';

    public bool $mon_has_lunch = false;

    public string $mon_lunch_from = '12:00';

    public string $mon_lunch_to = '13:00';

    public bool $tue_open = true;

    public string $tue_from = '09:00';

    public string $tue_to = '17:00';

    public bool $tue_has_lunch = false;

    public string $tue_lunch_from = '12:00';

    public string $tue_lunch_to = '13:00';

    public bool $wed_open = true;

    public string $wed_from = '09:00';

    public string $wed_to = '17:00';

    public bool $wed_has_lunch = false;

    public string $wed_lunch_from = '12:00';

    public string $wed_lunch_to = '13:00';

    public bool $thu_open = true;

    public string $thu_from = '09:00';

    public string $thu_to = '17:00';

    public bool $thu_has_lunch = false;

    public string $thu_lunch_from = '12:00';

    public string $thu_lunch_to = '13:00';

    public bool $fri_open = true;

    public string $fri_from = '09:00';

    public string $fri_to = '17:00';

    public bool $fri_has_lunch = false;

    public string $fri_lunch_from = '12:00';

    public string $fri_lunch_to = '13:00';

    public bool $sat_open = false;

    public string $sat_from = '09:00';

    public string $sat_to = '17:00';

    public bool $sat_has_lunch = false;

    public string $sat_lunch_from = '12:00';

    public string $sat_lunch_to = '13:00';

    // Global scheduling settings
    public int $session_interval_minutes = 10;

    public int $max_sessions_per_slot = 1;

    /** @var array<int, array{int, string, string}> */
    private const DAYS = [
        [0, 'sun', 'klinica.schedule.day_sun'],
        [1, 'mon', 'klinica.schedule.day_mon'],
        [2, 'tue', 'klinica.schedule.day_tue'],
        [3, 'wed', 'klinica.schedule.day_wed'],
        [4, 'thu', 'klinica.schedule.day_thu'],
        [5, 'fri', 'klinica.schedule.day_fri'],
        [6, 'sat', 'klinica.schedule.day_sat'],
    ];

    public function mount(): void
    {
        ClinicSchedule::seedDefaults();

        $rows = ClinicSchedule::query()->orderBy('day_of_week')->get()->keyBy('day_of_week');

        foreach (self::DAYS as [$idx, $prefix]) {
            if ($row = $rows->get($idx)) {
                $this->{$prefix.'_open'} = (bool) $row->is_open;
                $this->{$prefix.'_from'} = substr((string) $row->open_time, 0, 5);
                $this->{$prefix.'_to'} = substr((string) $row->close_time, 0, 5);
                $this->{$prefix.'_has_lunch'} = (bool) $row->has_lunch_break;
                $this->{$prefix.'_lunch_from'} = $row->lunch_break_from ? substr((string) $row->lunch_break_from, 0, 5) : '12:00';
                $this->{$prefix.'_lunch_to'} = $row->lunch_break_to ? substr((string) $row->lunch_break_to, 0, 5) : '13:00';
            }
        }

        $settings = ClinicSetting::current();
        $this->session_interval_minutes = $settings->session_interval_minutes ?? 10;
        $this->max_sessions_per_slot = $settings->max_sessions_per_slot ?? 1;
    }

    public function form(Schema $schema): Schema
    {
        $rows = [];

        foreach (self::DAYS as [$idx, $prefix, $labelKey]) {
            $rows[] = Grid::make(['default' => 1, 'sm' => 4])->schema([
                Toggle::make("{$prefix}_open")
                    ->label(__($labelKey))
                    ->live()
                    ->inline(false),
                TimePicker::make("{$prefix}_from")
                    ->label(__('klinica.schedule.label_opens_at'))
                    ->seconds(false)
                    ->visible(fn (Get $get): bool => (bool) $get("{$prefix}_open"))
                    ->required(fn (Get $get): bool => (bool) $get("{$prefix}_open")),
                TimePicker::make("{$prefix}_to")
                    ->label(__('klinica.schedule.label_closes_at'))
                    ->seconds(false)
                    ->visible(fn (Get $get): bool => (bool) $get("{$prefix}_open"))
                    ->required(fn (Get $get): bool => (bool) $get("{$prefix}_open")),
                Toggle::make("{$prefix}_has_lunch")
                    ->label(__('klinica.schedule.label_lunch'))
                    ->helperText(__('klinica.schedule.label_lunch_helper'))
                    ->live()
                    ->inline(false)
                    ->visible(fn (Get $get): bool => (bool) $get("{$prefix}_open")),
            ]);

            $rows[] = Grid::make(['default' => 1, 'sm' => 4])->schema([
                TimePicker::make("{$prefix}_lunch_from")
                    ->label(__('klinica.schedule.label_lunch_from'))
                    ->seconds(false)
                    ->visible(fn (Get $get): bool => (bool) $get("{$prefix}_open") && (bool) $get("{$prefix}_has_lunch"))
                    ->required(fn (Get $get): bool => (bool) $get("{$prefix}_open") && (bool) $get("{$prefix}_has_lunch"))
                    ->columnStart(2),
                TimePicker::make("{$prefix}_lunch_to")
                    ->label(__('klinica.schedule.label_lunch_until'))
                    ->seconds(false)
                    ->visible(fn (Get $get): bool => (bool) $get("{$prefix}_open") && (bool) $get("{$prefix}_has_lunch"))
                    ->required(fn (Get $get): bool => (bool) $get("{$prefix}_open") && (bool) $get("{$prefix}_has_lunch")),
            ]);
        }

        return $schema->components([
            Section::make(__('klinica.schedule.section_weekly'))
                ->description(__('klinica.schedule.section_weekly_desc'))
                ->schema($rows),

            Section::make(__('klinica.schedule.section_rules'))
                ->description(__('klinica.schedule.section_rules_desc'))
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('session_interval_minutes')
                            ->label(__('klinica.schedule.field_interval'))
                            ->helperText(__('klinica.schedule.field_interval_helper'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120)
                            ->suffix('min')
                            ->required(),
                        TextInput::make('max_sessions_per_slot')
                            ->label(__('klinica.schedule.field_max_sessions'))
                            ->helperText(__('klinica.schedule.field_max_sessions_helper'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(50)
                            ->required(),
                    ]),
                ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(__('klinica.schedule.save_action'))
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }

    public function save(): void
    {
        foreach (self::DAYS as [$idx, $prefix]) {
            ClinicSchedule::where('day_of_week', $idx)->update([
                'is_open' => (bool) $this->{$prefix.'_open'},
                'open_time' => $this->{$prefix.'_from'},
                'close_time' => $this->{$prefix.'_to'},
                'has_lunch_break' => (bool) $this->{$prefix.'_has_lunch'},
                'lunch_break_from' => $this->{$prefix.'_has_lunch'} ? $this->{$prefix.'_lunch_from'} : null,
                'lunch_break_to' => $this->{$prefix.'_has_lunch'} ? $this->{$prefix.'_lunch_to'} : null,
            ]);
        }

        ClinicSetting::current()->update([
            'session_interval_minutes' => (int) $this->session_interval_minutes,
            'max_sessions_per_slot' => (int) $this->max_sessions_per_slot,
        ]);

        Notification::make()
            ->title(__('klinica.schedule.saved'))
            ->success()
            ->send();
    }
}
