<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources;

use App\Actions\Tenant\CancelBookingAction;
use App\Actions\Tenant\ConfirmBookingAction;
use App\Actions\Tenant\CreatePatientFromBookingAction;
use App\Enums\BookingStatus;
use App\Filament\Tenant\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\Patient;
use App\Models\Service;
use App\Models\User;
use App\Services\Booking\AvailabilityService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    public static function canAccess(): bool
    {
        $user = auth('tenant')->user();

        return $user?->is_super_admin || ($user?->hasPermissionTo('view_bookings', 'tenant') ?? false);
    }

    public static function canCreate(): bool
    {
        $tenant = tenancy()->tenant;

        if ($tenant === null) {
            return false;
        }

        $monthlyCount = Booking::query()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        return $monthlyCount < $tenant->maxMonthlyBookings();
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()
            ->where('status', BookingStatus::Pending)
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Booking Details')->schema([
                Grid::make(2)->schema([
                    Select::make('patient_id')
                        ->label('Patient')
                        ->options(Patient::query()->get()->mapWithKeys(
                            fn (Patient $p) => [$p->id => "{$p->first_name} {$p->last_name}"]
                        ))
                        ->searchable()
                        ->nullable(),

                    Select::make('service_id')
                        ->label('Service')
                        ->options(Service::query()->where('is_active', true)->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set): void {
                            $startAt = $get('start_at');
                            $serviceId = $get('service_id');
                            if ($startAt && $serviceId) {
                                $service = Service::find($serviceId);
                                if ($service) {
                                    $end = app(AvailabilityService::class)->calculateEndTime(Carbon::parse((string) $startAt), $service);
                                    $set('end_at', $end->toDateTimeString());
                                }
                            }
                        }),

                    Select::make('employee_id')
                        ->label('Employee')
                        ->options(function (Get $get): array {
                            $startAt = $get('start_at');
                            $serviceId = $get('service_id');

                            $employees = User::query()->where('is_active', true)->get();

                            if (! $startAt || ! $serviceId) {
                                return $employees->pluck('name', 'id')->all();
                            }

                            $service = Service::find($serviceId);
                            $start = Carbon::parse((string) $startAt);
                            $end = $service
                                ? $start->copy()->addMinutes($service->duration_minutes)
                                : $start->copy()->addHour();

                            $bookedIds = Booking::query()
                                ->whereNotIn('status', [
                                    BookingStatus::Cancelled->value,
                                    BookingStatus::NoShow->value,
                                ])
                                ->where('start_at', '<', $end)
                                ->where('end_at', '>', $start)
                                ->whereNotNull('employee_id')
                                ->pluck('employee_id')
                                ->unique()
                                ->all();

                            return $employees->mapWithKeys(function (User $user) use ($bookedIds): array {
                                $label = in_array($user->id, $bookedIds)
                                    ? "{$user->name} — already booked at this time"
                                    : $user->name;

                                return [$user->id => $label];
                            })->all();
                        })
                        ->disableOptionWhen(function (string|int $value, Get $get): bool {
                            $startAt = $get('start_at');
                            $serviceId = $get('service_id');

                            if (! $startAt || ! $serviceId) {
                                return false;
                            }

                            $service = Service::find($serviceId);
                            $start = Carbon::parse((string) $startAt);
                            $end = $service
                                ? $start->copy()->addMinutes($service->duration_minutes)
                                : $start->copy()->addHour();

                            return Booking::query()
                                ->whereNotIn('status', [
                                    BookingStatus::Cancelled->value,
                                    BookingStatus::NoShow->value,
                                ])
                                ->where('start_at', '<', $end)
                                ->where('end_at', '>', $start)
                                ->where('employee_id', $value)
                                ->exists();
                        })
                        ->searchable()
                        ->live()
                        ->nullable(),

                    Select::make('status')
                        ->options(BookingStatus::class)
                        ->required()
                        ->native(false),

                    DateTimePicker::make('start_at')
                        ->required()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set): void {
                            $startAt = $get('start_at');
                            $serviceId = $get('service_id');
                            if ($startAt && $serviceId) {
                                $service = Service::find($serviceId);
                                if ($service) {
                                    $end = app(AvailabilityService::class)->calculateEndTime(Carbon::parse((string) $startAt), $service);
                                    $set('end_at', $end->toDateTimeString());
                                }
                            }
                        }),
                ]),
            ]),

            Section::make('Guest Information')
                ->description('Filled automatically from public booking form when no patient is selected.')
                ->collapsed()
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('guest_name')->nullable(),
                        TextInput::make('guest_email')->email()->nullable(),
                        TextInput::make('guest_phone')->tel()->nullable(),
                    ]),
                ]),

            Section::make('Notes')->schema([
                Textarea::make('notes')->rows(3)->columnSpanFull()->nullable(),
                Textarea::make('cancellation_reason')
                    ->rows(2)
                    ->nullable()
                    ->visible(fn (?BookingStatus $state): bool => $state === BookingStatus::Cancelled),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.first_name')
                    ->label('Patient')
                    ->formatStateUsing(fn ($state, Booking $record): string => $record->patient
                        ? "{$record->patient->first_name} {$record->patient->last_name}"
                        : ($record->guest_name ?? '—'))
                    ->searchable()
                    ->description(fn (Booking $record): ?string => $record->patient
                        ? null
                        : ($record->guest_email ?? $record->guest_phone)),

                TextColumn::make('service.name')->label('Service')->sortable(),
                TextColumn::make('employee.name')->label('Employee')->sortable()->toggleable(),
                TextColumn::make('start_at')->dateTime('D, M j Y · g:i A')->sortable()->label('Date & Time'),
                TextColumn::make('status')
                    ->badge()
                    ->sortable(),
            ])
            ->defaultSort('start_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->options(BookingStatus::class),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('confirm')
                    ->label('Confirm & Notify')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Confirm this booking?')
                    ->modalDescription('The patient will receive a confirmation email.')
                    ->visible(fn (Booking $record): bool => $record->status === BookingStatus::Pending)
                    ->action(function (Booking $record): void {
                        app(ConfirmBookingAction::class)->execute($record);
                        Notification::make()->title('Booking confirmed and patient notified.')->success()->send();
                    }),

                Action::make('cancel')
                    ->label('Cancel')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('cancellation_reason')->rows(2)->nullable(),
                    ])
                    ->visible(fn (Booking $record): bool => $record->status !== BookingStatus::Cancelled)
                    ->action(function (Booking $record, array $data): void {
                        app(CancelBookingAction::class)->execute($record, $data['cancellation_reason'] ?? null);
                        Notification::make()->title('Booking cancelled and patient notified.')->success()->send();
                    }),

                Action::make('create_patient')
                    ->label('Create Patient')
                    ->tooltip('Create a patient record from this booking\'s guest information')
                    ->color('info')
                    ->icon('heroicon-o-user-plus')
                    ->requiresConfirmation()
                    ->modalHeading('Create patient from this booking?')
                    ->modalDescription('A patient record will be created using the guest name, email, and phone. If a matching patient already exists, the booking will be linked to them instead.')
                    ->visible(fn (Booking $record): bool => $record->patient_id === null
                        && ($record->guest_name !== null || $record->guest_email !== null || $record->guest_phone !== null))
                    ->action(function (Booking $record): void {
                        $patient = app(CreatePatientFromBookingAction::class)->execute($record);
                        Notification::make()
                            ->title("Patient \"{$patient->first_name} {$patient->last_name}\" linked to this booking.")
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
