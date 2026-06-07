<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\BookingResource\Pages;

use App\Filament\Tenant\Resources\BookingResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    public function getSubheading(): ?string
    {
        return __('klinica.booking.page_description');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('export_bookings')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(route('gdpr.export.bookings'))
                ->openUrlInNewTab(),
        ];
    }
}
