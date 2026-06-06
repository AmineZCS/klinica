<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Actions\Central\ExtendTrialAction;
use App\Enums\TenantPlan;
use App\Filament\Admin\Resources\TenantResource\Pages;
use App\Models\Tenant;
use App\Services\IndexNowService;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup as ActionsBulkActionGroup;
use Filament\Actions\DeleteBulkAction as ActionsDeleteBulkAction;
use Filament\Actions\EditAction as ActionsEditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static string|\UnitEnum|null $navigationGroup = 'Tenants';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Tenant Details')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('id')
                        ->label('Subdomain')
                        ->required()
                        ->maxLength(63)
                        ->helperText('e.g. "myclinic" → myclinic.klinica.space')
                        ->visibleOn('create'),

                    Select::make('plan')
                        ->options(TenantPlan::class)
                        ->required(),

                    DateTimePicker::make('trial_ends_at')
                        ->label('Trial ends at'),

                    DateTimePicker::make('plan_expires_at')
                        ->label('Plan expires at'),

                    TextInput::make('paddle_customer_id')
                        ->label('Paddle Customer ID'),

                    TextInput::make('discount_code')
                        ->label('Discount Code'),

                    Toggle::make('is_active')
                        ->label('Active (clinic can log in and be accessed)')
                        ->default(true)
                        ->columnSpanFull(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->searchable()->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('plan')
                    ->badge()
                    ->formatStateUsing(fn (TenantPlan $state): string => $state->label())
                    ->color(fn (TenantPlan $state): string => $state->badgeColor()),
                TextColumn::make('trial_ends_at')->dateTime()->sortable(),
                TextColumn::make('plan_expires_at')->dateTime()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('plan')->options(TenantPlan::class),
                TernaryFilter::make('is_active')->label('Active status'),
            ])
            ->recordActions([
                Action::make('open_dashboard')
                    ->label('Open Dashboard')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (Tenant $record): string => route('admin.impersonate', $record))
                    ->openUrlInNewTab(),
                ActionsEditAction::make(),
                Action::make('toggle_active')
                    ->label(fn (Tenant $record): string => $record->is_active ? 'Disable' : 'Enable')
                    ->icon(fn (Tenant $record): string => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Tenant $record): string => $record->is_active ? 'danger' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Tenant $record): string => $record->is_active ? 'Disable Clinic' : 'Enable Clinic')
                    ->modalDescription(fn (Tenant $record): string => $record->is_active
                        ? 'This will block all logins and the public landing page for this clinic.'
                        : 'This will restore access to this clinic.')
                    ->action(fn (Tenant $record) => $record->update(['is_active' => ! $record->is_active])),
                Action::make('extend_trial')
                    ->label('Extend Trial')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        TextInput::make('days')
                            ->numeric()
                            ->required()
                            ->default(14)
                            ->minValue(1),
                    ])
                    ->action(function (Tenant $record, array $data): void {
                        app(ExtendTrialAction::class)->execute($record, (int) $data['days']);
                    }),
                Action::make('index_now')
                    ->label('Submit to IndexNow')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Submit to IndexNow')
                    ->modalDescription(fn (Tenant $record): string => "Submit {$record->tenantBaseUrl()}/ to search engine indexing via IndexNow.")
                    ->action(function (Tenant $record): void {
                        $success = app(IndexNowService::class)->submitTenant($record);

                        if ($success) {
                            Notification::make()
                                ->title('Submitted to IndexNow')
                                ->body("{$record->id} queued for search engine indexing.")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('IndexNow submission failed')
                                ->body('Check the application logs for details.')
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->toolbarActions([
                ActionsBulkActionGroup::make([
                    BulkAction::make('bulk_index_now')
                        ->label('Submit to IndexNow')
                        ->icon('heroicon-o-magnifying-glass')
                        ->requiresConfirmation()
                        ->modalHeading('Submit selected tenants to IndexNow')
                        ->modalDescription('This will submit the landing page URL of each selected tenant to IndexNow for search engine indexing.')
                        ->action(function (Collection $records): void {
                            $count = app(IndexNowService::class)->submitTenants($records);

                            Notification::make()
                                ->title("IndexNow: {$count}/{$records->count()} submitted")
                                ->body($count < $records->count() ? 'Some submissions failed. Check the application logs.' : 'All tenants queued for search engine indexing.')
                                ->color($count === $records->count() ? 'success' : 'warning')
                                ->send();
                        }),
                    ActionsDeleteBulkAction::make(),
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
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
