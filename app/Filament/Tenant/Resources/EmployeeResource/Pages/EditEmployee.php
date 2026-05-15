<?php

declare(strict_types=1);

namespace App\Filament\Tenant\Resources\EmployeeResource\Pages;

use App\Filament\Tenant\Resources\EmployeeResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function (DeleteAction $action, User $record): void {
                    if ($record->is_super_admin) {
                        Notification::make()
                            ->title('Cannot delete the clinic super-admin.')
                            ->danger()
                            ->send();

                        $action->cancel();
                    }

                    if ($record->id === auth()->id()) {
                        Notification::make()
                            ->title('You cannot delete your own account.')
                            ->danger()
                            ->send();

                        $action->cancel();
                    }
                }),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var User $record */
        $record = $this->getRecord();
        $data['roles'] = $record->roles->pluck('name')->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Roles are synced separately via afterSave
        unset($data['roles']);

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var User $record */
        $record = $this->getRecord();
        $roles = $this->data['roles'] ?? [];
        $record->syncRoles($roles);
    }
}
