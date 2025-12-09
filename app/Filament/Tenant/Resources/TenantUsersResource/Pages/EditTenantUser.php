<?php

namespace App\Filament\Tenant\Resources\TenantUsersResource\Pages;

use App\Enums\TenantRole;
use App\Filament\Tenant\Resources\TenantUsersResource;
use Filament\Facades\Filament;
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

        $record->update($data);

        if ($tenant) {
            // Always include current tenant in the assignment list.
            $tenantIds[] = $tenant->getKey();

            $attachData = collect($tenantIds)
                ->filter()
                ->unique()
                ->mapWithKeys(fn ($id) => [$id => ['role' => $role]])
                ->all();

            $record->tenants()->syncWithoutDetaching($attachData);

            foreach (array_keys($attachData) as $id) {
                $record->tenants()->updateExistingPivot($id, ['role' => $role]);
            }
        }

        return $record;
    }
}
