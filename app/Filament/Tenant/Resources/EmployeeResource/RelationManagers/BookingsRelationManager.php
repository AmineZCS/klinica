<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\EmployeeResource\RelationManagers;

use App\Filament\Tenant\Resources\BookingResource;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignedBookings';

    public function isReadOnly(): bool
    {
        return true;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Assigned Bookings')
            ->defaultSort('start_at', 'desc')
            ->columns([
                TextColumn::make('service.name')
                    ->label('Service')
                    ->sortable(),

                TextColumn::make('start_at')
                    ->label('Date & Time')
                    ->dateTime('D, M j Y · g:i A')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable(),

                TextColumn::make('patient.first_name')
                    ->label('Patient')
                    ->formatStateUsing(fn ($state, Booking $record): string => $record->patient
                        ? "{$record->patient->first_name} {$record->patient->last_name}"
                        : ($record->guest_name ?? '—'))
                    ->placeholder('—'),
            ])
            ->recordActions([
                Action::make('edit_booking')
                    ->label('View')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Booking $record): string => BookingResource::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab(),
            ]);
    }
}
