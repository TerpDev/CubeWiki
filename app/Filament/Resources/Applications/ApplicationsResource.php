<?php

namespace App\Filament\Resources\Applications;

use App\Filament\Resources\Applications\Pages\CreateApplications;
use App\Filament\Resources\Applications\Pages\EditApplications;
use App\Filament\Resources\Applications\Pages\ListApplications;
use App\Filament\Resources\Applications\Schemas\ApplicationsForm;
use App\Filament\Resources\Applications\Tables\ApplicationsTable;
use App\Models\Application;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ApplicationsResource extends Resource
{
    protected static ?string $tenantOwnershipRelationshipName = 'tenant'; // 👈 add this

    protected static ?string $model = Application::class;
    protected static ?int $navigationSort = 3;
    public static function getNavigationLabel(): string
    {
        return __('Applications');
    }

    public static function getPluralLabel(): string
    {
        return __('Applications');
    }

    public static function getLabel(): string
    {
        return __('Application');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Cube;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ApplicationsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApplicationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplications::route('/'),
            'create' => CreateApplications::route('/create'),
            'edit' => EditApplications::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('tenant');
    }
}
