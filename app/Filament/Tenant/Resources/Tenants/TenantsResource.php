<?php

namespace App\Filament\Tenant\Resources\Tenants;

use App\Filament\Tenant\Resources\Tenants\Pages\CreateTenants;
use App\Filament\Tenant\Resources\Tenants\Pages\EditTenants;
use App\Filament\Tenant\Resources\Tenants\Pages\ListTenants;
use App\Filament\Tenant\Resources\Tenants\Schemas\TenantsForm;
use App\Filament\Tenant\Resources\Tenants\Tables\TenantsTable;
use App\Models\Tenants;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TenantsResource extends Resource
{
    protected static ?string $model = Tenants::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Tenant';

    protected static bool $shouldRegisterNavigation = false;

    // Don't scope Tenants to the current tenant, so the tenant switcher and pages can see all of a user's tenants.
    protected static bool $isScopedToTenant = false;

    public static function form(Schema $schema): Schema
    {
        return TenantsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenants::route('/create'),
            'edit' => EditTenants::route('/{record}/edit'),
        ];
    }
}
