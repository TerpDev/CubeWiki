<?php

namespace App\Filament\Tenant\Resources\TenantUsersResource\Pages;

use App\Enums\TenantRole;
use App\Filament\Tenant\Resources\TenantUsersResource;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class EditTenantUser extends EditRecord
{
    protected static string $resource = TenantUsersResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $tenant = Filament::getTenant();
        $role = Arr::pull($data, 'role', TenantRole::MEMBER->value);
        $tenantIds = Arr::wrap(Arr::pull($data, 'tenant_ids', []));

        // Ensure tenantIds is not empty - filter and make unique
        $tenantIds = collect($tenantIds)->filter()->unique()->values()->all();

        // Check if user would have at least 1 tenant after the update
        if (empty($tenantIds)) {
            Notification::make()
                ->title('Validation Error')
                ->body('A user must belong to at least 1 tenant.')
                ->danger()
                ->send();

            // Halt the update - don't save
            $this->halt();
        }

        $record->update($data);

        if ($tenant) {
            // Get all tenants the current user owns/manages
            $currentUserTenantIds = auth()->user()?->tenants()->pluck('tenants.id')->all() ?? [];

            // Build attach data for selected tenants
            $attachData = collect($tenantIds)
                ->mapWithKeys(fn ($id) => [$id => ['role' => $role]])
                ->all();

            // Get the user's current tenant relationships that the current user can manage
            $currentManagedTenantIds = $record->tenants()
                ->whereIn('tenants.id', $currentUserTenantIds)
                ->pluck('tenants.id')
                ->all();

            // Detach tenants that were deselected (only those managed by current user)
            $tenantsToDetach = array_diff($currentManagedTenantIds, array_keys($attachData));

            // Check if detaching would leave user with no tenants at all
            $allUserTenantIds = $record->tenants()->pluck('tenants.id')->all();
            $remainingTenantIds = array_diff($allUserTenantIds, $tenantsToDetach);
            $remainingTenantIds = array_merge($remainingTenantIds, array_keys($attachData));
            $remainingTenantIds = array_unique($remainingTenantIds);

            if (empty($remainingTenantIds)) {
                Notification::make()
                    ->title('Validation Error')
                    ->body('A user must belong to at least 1 tenant. Cannot remove all tenants.')
                    ->danger()
                    ->send();

                // Halt the update
                $this->halt();
            }

            // Detach tenants that were removed
            if (! empty($tenantsToDetach)) {
                $record->tenants()->detach($tenantsToDetach);
            }

            // Sync the selected tenants (attach new ones and update existing)
            $record->tenants()->syncWithoutDetaching($attachData);

            // Update roles for all selected tenants
            foreach (array_keys($attachData) as $id) {
                $record->tenants()->updateExistingPivot($id, ['role' => $role]);
            }
        }

        return $record;
    }
}
