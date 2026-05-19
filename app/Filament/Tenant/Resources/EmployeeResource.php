<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\EmployeeResource\Pages;
use App\Filament\Tenant\Resources\EmployeeResource\RelationManagers\BookingsRelationManager;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class EmployeeResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    public static function getModelLabel(): string
    {
        return __('klinica.employee.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('klinica.employee.label_plural');
    }

    public static function canAccess(): bool
    {
        $user = auth('tenant')->user();

        return $user?->is_super_admin || ($user?->hasPermissionTo('view_employees', 'tenant') ?? false);
    }

    public static function canCreate(): bool
    {
        $tenant = tenancy()->tenant;

        return $tenant !== null && User::query()->count() < $tenant->maxEmployees();
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) User::query()->where('is_active', true)->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'primary';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->label(__('klinica.employee.field_name'))->required()->maxLength(255),
                    TextInput::make('email')->label(__('klinica.employee.field_email'))->email()->required()->unique(ignoreRecord: true),
                    TextInput::make('password')
                        ->label(__('klinica.employee.field_password'))
                        ->password()
                        ->minLength(8)
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create'),
                    Toggle::make('is_active')
                        ->label(__('klinica.employee.field_active'))
                        ->default(true),
                    Select::make('roles')
                        ->label(__('klinica.employee.field_role'))
                        ->options(
                            Role::where('guard_name', 'tenant')
                                ->pluck('name', 'name')
                                ->mapWithKeys(fn (string $name) => [$name => ucfirst($name)])
                        )
                        ->multiple()
                        ->preload()
                        ->columnSpanFull(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('klinica.employee.field_name'))->searchable()->sortable(),
                TextColumn::make('email')->label(__('klinica.employee.field_email'))->searchable(),
                TextColumn::make('roles.name')->label(__('klinica.employee.field_role'))->badge(),
                IconColumn::make('is_active')->label(__('klinica.employee.field_active'))->boolean()->sortable(),
                TextColumn::make('created_at')->label(__('klinica.common.created_at'))->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
