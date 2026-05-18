<?php

declare(strict_types=1);

namespace App\Filament\Admin\Pages;

use App\Models\FeatureRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class FeatureRequestsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-light-bulb';

    protected static string|\UnitEnum|null $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 20;

    protected static ?string $title = 'Feature Requests';

    protected string $view = 'filament.admin.pages.feature-requests-page';

    public static function getNavigationLabel(): string
    {
        return 'Feature Requests';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(FeatureRequest::query()->with('tenant')->latest())
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->weight('medium')
                    ->description(fn (FeatureRequest $record): string => str($record->description)->limit(90)->toString()),
                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->searchable(query: fn ($query, string $search) => $query->whereHas('tenant', fn ($tenantQuery) => $tenantQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('id', 'like', "%{$search}%")))
                    ->default(fn (FeatureRequest $record): string => $record->tenant_id),
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
                TextColumn::make('requested_by_name')
                    ->label('Requested by')
                    ->description(fn (FeatureRequest $record): string => (string) ($record->requested_by_email ?? '')),
                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('update_status')
                    ->label('Update')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        Select::make('status')
                            ->options(FeatureRequest::statusOptions())
                            ->required(),
                        Textarea::make('admin_notes')
                            ->rows(5)
                            ->maxLength(3000),
                    ])
                    ->fillForm(fn (FeatureRequest $record): array => [
                        'status' => $record->status,
                        'admin_notes' => $record->admin_notes,
                    ])
                    ->action(fn (FeatureRequest $record, array $data) => $record->update([
                        'status' => $data['status'],
                        'admin_notes' => $data['admin_notes'] ?? null,
                    ])),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}
