<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\EmployeeResource\Pages;

use App\Filament\Tenant\Resources\EmployeeResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Roles are synced in afterCreate
        unset($data['roles']);

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var User $record */
        $record = $this->record;
        $roles = $this->data['roles'] ?? [];
        $record->syncRoles($roles);
    }
}
