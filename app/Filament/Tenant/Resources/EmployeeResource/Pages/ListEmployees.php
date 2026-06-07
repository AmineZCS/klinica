<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\EmployeeResource\Pages;

use App\Filament\Tenant\Resources\EmployeeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    public function getSubheading(): ?string
    {
        return __('klinica.employee.page_description');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
