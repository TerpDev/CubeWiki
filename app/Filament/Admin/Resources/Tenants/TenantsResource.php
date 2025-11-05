<?php

namespace App\Filament\Admin\Resources\Tenants;

use App\Filament\Admin\Resources\Tenants\Pages\CreateTenants;
use App\Filament\Admin\Resources\Tenants\Pages\EditTenants;
use App\Filament\Admin\Resources\Tenants\Pages\ListTenants;
use App\Filament\Admin\Resources\Tenants\Pages;
use App\Filament\Admin\Resources\Tenants\Schemas\TenantsForm;
use App\Filament\Admin\Resources\Tenants\Tables\TenantsTable;
use App\Models\Tenants;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;


class TenantsResource extends Resource
{
// This resource represents the tenant model itself,
// so it must not be scoped to a tenant.
    protected static bool $isScopedToTenant = false;
    protected static ?string $tenantOwnershipRelationshipName = null;
    protected static ?string $model = Tenants::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'name';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Schema $schema): Schema
    {
        return TenantsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
    }


    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenants::route('/create'),
            'edit' => EditTenants::route('/{record}/edit'),
            'test-api-token' => Pages\TestApiToken::route('/test-api-token'),
        ];
    }

    // ensure counts are available on the queries used by Filament tables
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['applications'])->withCount(['users', 'applications']);
    }
}
