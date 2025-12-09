<?php

namespace App\Filament\Tenant\Resources\TenantUsersResource\Pages;

use App\Enums\TenantRole;
use App\Filament\Tenant\Resources\TenantUsersResource;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class CreateTenantUser extends CreateRecord
{
    protected static string $resource = TenantUsersResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            throw new \RuntimeException('No tenant resolved for this request.');
        }

        $role = Arr::pull($data, 'role', TenantRole::MEMBER->value);
        $tenantIds = Arr::wrap(Arr::pull($data, 'tenant_ids', []));

        /** @var \App\Models\User $user */
        $user = static::getModel()::create($data);

        // Always include the current tenant in the assignment list.
        $tenantIds[] = $tenant->getKey();

        $attachData = collect($tenantIds)
            ->filter()
            ->unique()
            ->mapWithKeys(fn ($id) => [$id => ['role' => $role]])
            ->all();

        $user->tenants()->syncWithoutDetaching($attachData);

        return $user;
    }
}
