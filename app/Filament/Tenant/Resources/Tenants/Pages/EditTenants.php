<?php

namespace App\Filament\Tenant\Resources\Tenants\Pages;

use App\Filament\Tenant\Resources\Tenants\TenantsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTenants extends EditRecord
{
    protected static string $resource = TenantsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
