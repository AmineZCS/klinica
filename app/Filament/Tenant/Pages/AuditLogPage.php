<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class AuditLogPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|\UnitEnum|null $navigationGroup = null;

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.tenant.pages.audit-log-page';

    public function getTitle(): string
    {
        return __('klinica.audit_log.nav_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('klinica.audit_log.nav_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('klinica.settings.nav_group');
    }

    public static function canAccess(): bool
    {
        $user = auth('tenant')->user();

        return $user?->is_super_admin || ($user?->hasPermissionTo('view_audit_log', 'tenant') ?? false);
    }

    /** @return array<string, string> */
    private static function subjectTypeLabels(): array
    {
        return [
            'App\\Models\\Booking' => __('klinica.audit_log.subject_booking'),
            'App\\Models\\Patient' => __('klinica.audit_log.subject_patient'),
            'App\\Models\\Service' => __('klinica.audit_log.subject_service'),
            'App\\Models\\ClinicSetting' => __('klinica.audit_log.subject_clinic_settings'),
            'App\\Models\\User' => __('klinica.audit_log.subject_employee'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Activity::query()->latest())
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('klinica.audit_log.col_time'))
                    ->dateTime('d M Y, H:i')
                    ->description(fn (Activity $record): string => $record->created_at?->diffForHumans() ?? '')
                    ->sortable()
                    ->width('170px'),

                TextColumn::make('causer.name')
                    ->label(__('klinica.audit_log.col_user'))
                    ->default('—')
                    ->searchable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('event')
                    ->label(__('klinica.audit_log.col_event'))
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'created' => __('klinica.audit_log.event_created'),
                        'updated' => __('klinica.audit_log.event_updated'),
                        'deleted' => __('klinica.audit_log.event_deleted'),
                        default => ucfirst($state ?? 'unknown'),
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('subject_type')
                    ->label(__('klinica.audit_log.col_resource'))
                    ->formatStateUsing(function (?string $state, Activity $record): string {
                        $label = static::subjectTypeLabels()[$state ?? ''] ?? class_basename($state ?? '');
                        $subject = $record->subject;
                        $name = null;

                        if ($subject !== null) {
                            $name = $subject->name ?? $subject->title ?? ('#'.$subject->getKey());
                        } elseif ($record->subject_id !== null) {
                            $name = '#'.$record->subject_id.' '.__('klinica.audit_log.deleted_suffix');
                        }

                        return $name !== null ? "{$label}: {$name}" : $label;
                    })
                    ->description(fn (Activity $record): string => $record->description ?? ''),

                TextColumn::make('attribute_changes')
                    ->label(__('klinica.audit_log.col_changes'))
                    ->formatStateUsing(function (mixed $state, Activity $record): string {
                        $changes = $record->attribute_changes;

                        if ($changes === null || $changes->isEmpty()) {
                            return '—';
                        }

                        $old = $changes->get('old', []);
                        $new = $changes->get('attributes', []);

                        if (empty($old) && ! empty($new)) {
                            $keys = array_keys($new);

                            return implode(', ', array_slice($keys, 0, 4))
                                .(count($keys) > 4 ? ' '.__('klinica.audit_log.more', ['count' => count($keys) - 4]) : '');
                        }

                        $changed = array_keys(array_filter(
                            (array) $new,
                            fn (mixed $v, string $k): bool => ($old[$k] ?? null) !== $v,
                            ARRAY_FILTER_USE_BOTH
                        ));

                        if (empty($changed)) {
                            return '—';
                        }

                        return implode(', ', array_slice($changed, 0, 4))
                            .(count($changed) > 4 ? ' '.__('klinica.audit_log.more', ['count' => count($changed) - 4]) : '');
                    })
                    ->wrap()
                    ->html()
                    ->tooltip(fn (Activity $record): ?string => $this->buildDiffTooltip($record))
                    ->extraAttributes(['class' => 'font-mono text-xs']),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->label(__('klinica.audit_log.filter_event'))
                    ->options([
                        'created' => __('klinica.audit_log.event_created'),
                        'updated' => __('klinica.audit_log.event_updated'),
                        'deleted' => __('klinica.audit_log.event_deleted'),
                    ]),

                SelectFilter::make('subject_type')
                    ->label(__('klinica.audit_log.filter_resource'))
                    ->options(static::subjectTypeLabels()),

                Filter::make('today')
                    ->label(__('klinica.audit_log.filter_today'))
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', today())),

                Filter::make('this_week')
                    ->label(__('klinica.audit_log.filter_this_week'))
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([25, 50, 100])
            ->recordAction(null)
            ->striped();
    }

    private function buildDiffTooltip(Activity $record): ?string
    {
        $changes = $record->attribute_changes;

        if ($changes === null || $changes->isEmpty()) {
            return null;
        }

        $old = (array) $changes->get('old', []);
        $new = (array) $changes->get('attributes', []);

        if (empty($old)) {
            $parts = [];

            foreach (array_slice($new, 0, 10, true) as $key => $value) {
                $parts[] = "{$key}: ".(is_string($value) || is_numeric($value) ? $value : json_encode($value));
            }

            return implode("\n", $parts);
        }

        $parts = [];

        foreach (array_slice($new, 0, 10, true) as $key => $value) {
            if (($old[$key] ?? null) !== $value) {
                $oldVal = is_string($old[$key] ?? null) || is_numeric($old[$key] ?? null) ? ($old[$key] ?? 'null') : json_encode($old[$key] ?? null);
                $newVal = is_string($value) || is_numeric($value) ? $value : json_encode($value);
                $parts[] = "{$key}: {$oldVal} → {$newVal}";
            }
        }

        return empty($parts) ? null : implode("\n", $parts);
    }
}
