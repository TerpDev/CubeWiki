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

        $record->update($data);

        if ($tenant) {
            $record->tenants()->syncWithoutDetaching([
                $tenant->getKey() => ['role' => $role],
            ]);

            $record->tenants()->updateExistingPivot($tenant->getKey(), ['role' => $role]);
        }

        return $record;
    }
}
