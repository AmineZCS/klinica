<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\PatientResource\Pages;

use App\Filament\Tenant\Resources\PatientResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPatient extends EditRecord
{
    protected static string $resource = PatientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
