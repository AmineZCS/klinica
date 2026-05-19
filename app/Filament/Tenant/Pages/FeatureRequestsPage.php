<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Pages;

use App\Models\FeatureRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class FeatureRequestsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-light-bulb';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 999;

    public function getTitle(): string
    {
        return __('klinica.feature_requests.title');
    }

    protected string $view = 'filament.tenant.pages.feature-requests-page';

    public static function getNavigationLabel(): string
    {
        return __('klinica.feature_requests.nav_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('klinica.settings.nav_group');
    }

    public static function canAccess(): bool
    {
        return auth('tenant')->check();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('request_feature')
                ->label(__('klinica.feature_requests.action_new'))
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->schema([
                    TextInput::make('title')
                        ->label(__('klinica.feature_requests.field_title'))
                        ->required()
                        ->maxLength(255)
                        ->placeholder(__('klinica.feature_requests.field_title_placeholder')),
                    Select::make('priority')
                        ->label(__('klinica.feature_requests.field_priority'))
                        ->options(FeatureRequest::priorityOptions())
                        ->default(FeatureRequest::PRIORITY_MEDIUM)
                        ->required(),
                    Textarea::make('description')
                        ->label(__('klinica.feature_requests.field_description'))
                        ->rows(5)
                        ->required()
                        ->maxLength(3000)
                        ->placeholder(__('klinica.feature_requests.field_description_placeholder')),
                ])
                ->action(function (array $data): void {
                    $tenant = tenancy()->tenant;
                    $user = auth('tenant')->user();

                    if ($tenant === null || $user === null) {
                        Notification::make()
                            ->title(__('klinica.feature_requests.notification_error'))
                            ->danger()
                            ->send();

                        return;
                    }

                    FeatureRequest::query()->create([
                        'tenant_id' => (string) $tenant->getTenantKey(),
                        'requested_by_name' => $user->name,
                        'requested_by_email' => $user->email,
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'priority' => $data['priority'],
                        'status' => FeatureRequest::STATUS_PENDING,
                    ]);

                    Notification::make()
                        ->title(__('klinica.feature_requests.notification_success'))
                        ->success()
                        ->send();
                }),
        ];
    }

    public function table(Table $table): Table
    {
        $tenantId = (string) (tenancy()->tenant?->getTenantKey() ?? '');

        return $table
            ->query(
                FeatureRequest::query()
                    ->where('tenant_id', $tenantId)
                    ->latest()
            )
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->weight('medium')
                    ->description(fn (FeatureRequest $record): string => str($record->description)->limit(90)->toString()),
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        FeatureRequest::PRIORITY_HIGH => 'danger',
                        FeatureRequest::PRIORITY_MEDIUM => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => FeatureRequest::priorityOptions()[$state] ?? ucfirst($state)),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        FeatureRequest::STATUS_COMPLETED => 'success',
                        FeatureRequest::STATUS_PLANNED => 'info',
                        FeatureRequest::STATUS_REVIEWING => 'warning',
                        FeatureRequest::STATUS_REJECTED => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => FeatureRequest::statusOptions()[$state] ?? ucfirst($state)),
                TextColumn::make('created_at')
                    ->label(__('klinica.feature_requests.col_requested'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
                TextColumn::make('admin_notes')
                    ->label(__('klinica.feature_requests.col_admin_notes'))
                    ->placeholder('—')
                    ->limit(70)
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50])
            ->recordAction(null);
    }
}
