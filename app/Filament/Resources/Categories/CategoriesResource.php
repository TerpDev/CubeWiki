<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages\CreateCategories;
use App\Filament\Resources\Categories\Pages\EditCategories;
use App\Filament\Resources\Categories\Pages\ListCategories;
use App\Filament\Resources\Categories\Schemas\CategoriesForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CategoriesResource extends Resource
{
    protected static ?string $tenantOwnershipRelationshipName = 'tenant'; // 👈 Add this

    protected static ?string $model = Category::class;

    public static function getNavigationLabel(): string
    {
        return __('Categories');
    }

    protected static ?int $navigationSort = 3;

    public static function getPluralLabel(): string
    {
        return __('Categories');
    }

    public static function getLabel(): string
    {
        return __('Category');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Category';

    public static function form(Schema $schema): Schema
    {
        return CategoriesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategories::route('/create'),
            'edit' => EditCategories::route('/{record}/edit'),
        ];
    }
}
