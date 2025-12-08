<?php

namespace App\Filament\Tenant\Resources\TenantUsersResource\Pages;

use App\Filament\Tenant\Resources\TenantUsersResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListTenantUsers extends ListRecords
{
    protected static string $resource = TenantUsersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
