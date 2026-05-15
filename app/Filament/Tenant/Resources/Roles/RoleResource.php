<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\Roles;

use App\Filament\Tenant\Resources\Roles\Pages\CreateRole;
use App\Filament\Tenant\Resources\Roles\Pages\EditRole;
use App\Filament\Tenant\Resources\Roles\Pages\ListRoles;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 10;

    public static function canAccess(): bool
    {
        $user = auth('tenant')->user();

        if ($user === null) {
            return false;
        }

        return $user->is_super_admin || $user->hasPermissionTo('manage_roles', 'tenant');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('guard_name', 'tenant');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Role Details')->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(table: 'roles', column: 'name', ignoreRecord: true)
                    ->helperText('e.g. "receptionist", "nurse", "doctor"'),

                Select::make('permissions')
                    ->multiple()
                    ->relationship(
                        name: 'permissions',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('guard_name', 'tenant'),
                    )
                    ->options(
                        fn () => Permission::where('guard_name', 'tenant')
                            ->pluck('name', 'id')
                            ->map(fn (string $name) => str_replace('_', ' ', ucfirst($name)))
                    )
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label('Permissions')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Users')
                    ->badge()
                    ->color('info'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (Role $record): bool => in_array($record->name, ['admin', 'employee'])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
