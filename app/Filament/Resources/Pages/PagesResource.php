<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\CreatePages;
use App\Filament\Resources\Pages\Pages\EditPages;
use App\Filament\Resources\Pages\Pages\ListPages;
use App\Filament\Resources\Pages\Schemas\PagesForm;
use App\Filament\Resources\Pages\Tables\PagesTable;
use App\Models\Page;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PagesResource extends Resource
{
    protected static ?string $tenantOwnershipRelationshipName = 'tenant';

    protected static ?string $model = Page::class;

    public static function getNavigationLabel(): string
    {
        return __('Pages');
    }

    public static function getPluralLabel(): string
    {
        return __('Pages');
    }

    public static function getLabel(): string
    {
        return __('Page');
    }

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::when(
            Filament::getTenant(),
            fn ($query, $tenant) => $query->where('tenant_id', $tenant->getKey())
        )->count();

        return (string) $count;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Page';

    public static function form(Schema $schema): Schema
    {
        return PagesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagesTable::configure($table);
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
            'index' => ListPages::route('/'),
            'create' => CreatePages::route('/create'),
            'edit' => EditPages::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $tenant = Filament::getTenant();

        if ($tenant) {
            $query->where('tenant_id', $tenant->getKey());
        }

        return $query;
    }
}
