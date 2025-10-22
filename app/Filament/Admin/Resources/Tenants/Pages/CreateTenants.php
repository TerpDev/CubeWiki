<?php

namespace App\Filament\Admin\Resources\Tenants\Pages;

use App\Filament\Admin\Resources\Tenants\TenantsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenants extends CreateRecord
{
    protected static string $resource = TenantsResource::class;
}
