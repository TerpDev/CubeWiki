<?php

namespace App\Filament\Tenant\Resources;

use App\Enums\TenantRole;
use App\Filament\Tenant\Resources\TenantUsersResource\Pages\CreateTenantUser;
use App\Filament\Tenant\Resources\TenantUsersResource\Pages\EditTenantUser;
use App\Filament\Tenant\Resources\TenantUsersResource\Pages\ListTenantUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class TenantUsersResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $tenantOwnershipRelationshipName = 'tenants';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $slug = 'users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canViewAny(): bool
    {
        return static::currentUserCanManageTenant();
    }

    public static function canCreate(): bool
    {
        return static::currentUserCanManageTenant();
    }

    public static function canEdit($record): bool
    {
        return static::currentUserCanManageTenant();
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    protected static function currentUserCanManageTenant(): bool
    {
        $tenant = Filament::getTenant();
        $user = auth()->user();

        if (! $tenant || ! $user) {
            return false;
        }

        if ($user->email === 'admin@admin.com') {
            return true;
        }

        $role = $user->roleForTenant($tenant);

        return $role === TenantRole::OWNER->value;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('John Doe'),

                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('john@example.com'),

                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255)
                    ->placeholder('Enter password')
                    ->helperText('Minimal length: 8 characters.'),

                Select::make('role')
                    ->label('Role')
                    ->options(TenantRole::options())
                    ->required()
                    ->default(TenantRole::MEMBER->value)
                    ->native(false)
                    ->columnSpanFull()
                    ->live()
                    ->afterStateHydrated(function (Select $component, ?User $record): void {
                        if (! $record) {
                            return;
                        }

                        $tenantRole = $record->roleForTenant(Filament::getTenant());

                        $component->state($tenantRole ?? TenantRole::MEMBER->value);
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        TenantRole::OWNER->value => 'success',
                        default => 'gray',
                    })
                    ->getStateUsing(function (User $record) {
                        return $record->roleForTenant(Filament::getTenant()) ?? TenantRole::MEMBER->value;
                    })
                    ->sortable(false),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn () => static::currentUserCanManageTenant()),
                Action::make('remove')
                    ->label('Remove from tenant')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn () => static::currentUserCanManageTenant())
                    ->action(function (User $record): void {
                        $tenant = Filament::getTenant();

                        if (! $tenant) {
                            return;
                        }

                        $record->tenants()->detach($tenant->getKey());
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenantUsers::route('/'),
            'create' => CreateTenantUser::route('/create'),
            'edit' => EditTenantUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            // Hard guard to avoid leaking users when no tenant is resolved.
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->with([
                'tenants' => fn ($relation) => $relation
                    ->where('tenants.id', $tenant->getKey())
                    ->withPivot('role'),
            ])
            ->whereHas('tenants', fn ($q) => $q->where('tenants.id', $tenant->getKey()));
    }
}
