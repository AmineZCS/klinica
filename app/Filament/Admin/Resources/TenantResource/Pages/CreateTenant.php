<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\TenantResource\Pages;

use App\Actions\Central\CreateTenantAction;
use App\Filament\Admin\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $action = app(CreateTenantAction::class);

        return $action->execute([
            'name' => $data['name'],
            'subdomain' => $data['id'],
        ]);
    }
}
