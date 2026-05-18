<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Actions\Central\ExtendTrialAction;
use App\Enums\TenantPlan;
use App\Filament\Admin\Resources\SubscriptionResource\Pages;
use App\Models\Tenant;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static string|\UnitEnum|null $navigationGroup = 'Subscriptions';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Manage Subscriptions';

    protected static ?string $modelLabel = 'Subscription';

    protected static ?string $pluralModelLabel = 'Subscriptions';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Subscription Details')->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->label('Clinic name')
                        ->disabled()
                        ->dehydrated(false),

                    TextInput::make('id')
                        ->label('Tenant ID / subdomain')
                        ->disabled()
                        ->dehydrated(false),

                    Select::make('plan')
                        ->options(TenantPlan::class)
                        ->required(),

                    Select::make('plan_tier')
                        ->label('Trial/plan tier')
                        ->options([
                            'starter' => 'Starter tier',
                            'pro' => 'Pro tier',
                        ])
                        ->required()
                        ->helperText('Used for trial type and plan limits.'),

                    DateTimePicker::make('trial_ends_at')
                        ->label('Trial ends at')
                        ->seconds(false),

                    DateTimePicker::make('plan_expires_at')
                        ->label('Paid plan expires at')
                        ->seconds(false),

                    TextInput::make('paddle_customer_id')
                        ->label('Paddle customer ID'),

                    TextInput::make('paddle_subscription_id')
                        ->label('Paddle subscription ID'),

                    TextInput::make('discount_code')
                        ->label('Discount code'),

                    Toggle::make('is_active')
                        ->label('Account active')
                        ->columnSpanFull(),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Tenant')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Clinic')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('plan')
                    ->badge()
                    ->formatStateUsing(fn (TenantPlan $state): string => $state->label())
                    ->color(fn (TenantPlan $state): string => $state->badgeColor())
                    ->sortable(),

                TextColumn::make('plan_tier')
                    ->label('Tier')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => $state === 'pro' ? 'success' : 'primary')
                    ->sortable(),

                TextColumn::make('trial_ends_at')
                    ->label('Trial ends')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('trial_days_left')
                    ->label('Trial left')
                    ->state(function (Tenant $record): string {
                        if ($record->plan !== TenantPlan::Trial) {
                            return 'Not on trial';
                        }

                        $days = $record->trialDaysRemaining();

                        return $days > 0 ? "{$days} days" : 'Expired';
                    })
                    ->badge()
                    ->color(function (Tenant $record): string {
                        if ($record->plan !== TenantPlan::Trial) {
                            return 'gray';
                        }

                        $days = $record->trialDaysRemaining();

                        return match (true) {
                            $days <= 0 => 'danger',
                            $days <= 3 => 'warning',
                            default => 'success',
                        };
                    }),

                TextColumn::make('plan_expires_at')
                    ->label('Plan expires')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('plan')
                    ->options(TenantPlan::class),

                SelectFilter::make('plan_tier')
                    ->label('Tier')
                    ->options([
                        'starter' => 'Starter',
                        'pro' => 'Pro',
                    ]),

                Filter::make('active_trials')
                    ->label('Active trials')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('plan', TenantPlan::Trial->value)
                        ->where('trial_ends_at', '>', now())),

                Filter::make('expired_trials')
                    ->label('Expired trials')
                    ->query(fn (Builder $query): Builder => $query
                        ->where('plan', TenantPlan::Trial->value)
                        ->whereNotNull('trial_ends_at')
                        ->where('trial_ends_at', '<=', now())),

                TernaryFilter::make('is_active')
                    ->label('Account active'),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('extend_trial')
                    ->label('Extend Trial')
                    ->icon('heroicon-o-clock')
                    ->color('warning')
                    ->visible(fn (Tenant $record): bool => $record->plan === TenantPlan::Trial)
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
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
