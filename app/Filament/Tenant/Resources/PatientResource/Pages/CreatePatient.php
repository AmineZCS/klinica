<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\PatientResource\Pages;

use App\Filament\Tenant\Resources\PatientResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;
}
