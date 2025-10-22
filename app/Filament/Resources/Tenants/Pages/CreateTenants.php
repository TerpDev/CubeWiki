<?php

namespace App\Filament\Resources\Tenants\Pages;

use App\Filament\Resources\Tenants\TenantsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenants extends CreateRecord
{
    protected static string $resource = TenantsResource::class;
}
