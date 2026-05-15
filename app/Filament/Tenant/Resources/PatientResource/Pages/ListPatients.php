<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\PatientResource\Pages;

use App\Filament\Tenant\Resources\PatientResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatients extends ListRecords
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('export_patients')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->url(route('gdpr.export.patients'))
                ->openUrlInNewTab(),
        ];
    }
}
