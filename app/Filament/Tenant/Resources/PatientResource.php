<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources;

use App\Enums\Gender;
use App\Filament\Tenant\Resources\PatientResource\Pages;
use App\Filament\Tenant\Resources\PatientResource\RelationManagers\BookingsRelationManager;
use App\Models\Patient;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    public static function getModelLabel(): string
    {
        return __('klinica.patient.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('klinica.patient.label_plural');
    }

    public static function canAccess(): bool
    {
        $user = auth('tenant')->user();

        if (! $user) {
            return false;
        }

        try {
            return (bool) $user->hasPermissionTo('view_patients', 'tenant');
        } catch (\Throwable) {
            return false;
        }
    }

    public static function canCreate(): bool
    {
        $tenant = tenancy()->tenant;

        return $tenant !== null && Patient::query()->count() < $tenant->maxPatients();
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Patient::query()->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'info';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('klinica.patient.personal_info'))->schema([
                Grid::make(2)->schema([
                    TextInput::make('first_name')->label(__('klinica.patient.field_first_name'))->required()->maxLength(255),
                    TextInput::make('last_name')->label(__('klinica.patient.field_last_name'))->required()->maxLength(255),
                    TextInput::make('email')->label(__('klinica.patient.field_email'))->email()->nullable(),
                    TextInput::make('phone')->label(__('klinica.patient.field_phone'))->tel()->required(),
                    DatePicker::make('date_of_birth')->label(__('klinica.patient.field_dob')),
                    Select::make('gender')
                        ->label(__('klinica.patient.field_gender'))
                        ->options(collect(Gender::cases())->mapWithKeys(
                            fn (Gender $g) => [$g->value => $g->label()]
                        ))
                        ->nullable(),
                ]),
            ]),
            Section::make(__('klinica.patient.clinical_notes'))->schema([
                Textarea::make('health_notes')
                    ->label(__('klinica.patient.health_notes'))
                    ->rows(4)
                    ->nullable(),
                Textarea::make('notes')->label(__('klinica.patient.field_notes'))->rows(3)->nullable(),
                Toggle::make('is_active')->label(__('klinica.common.is_active'))->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label(__('klinica.patient.field_first_name'))->searchable()->sortable(),
                TextColumn::make('last_name')->label(__('klinica.patient.field_last_name'))->searchable()->sortable(),
                TextColumn::make('email')->label(__('klinica.patient.field_email'))->searchable()->toggleable(),
                TextColumn::make('phone')->label(__('klinica.patient.field_phone'))->searchable(),
                IconColumn::make('is_active')->label(__('klinica.common.is_active'))->boolean()->sortable(),
                TextColumn::make('created_at')->label(__('klinica.common.created_at'))->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
            ])
            ->actions([EditAction::make()])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BookingsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
