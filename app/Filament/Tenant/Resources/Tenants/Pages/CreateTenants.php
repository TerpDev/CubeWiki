<?php

namespace App\Filament\Tenant\Resources\Tenants\Pages;

use App\Filament\Tenant\Resources\Tenants\TenantsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenants extends CreateRecord
{
    protected static string $resource = TenantsResource::class;
}
