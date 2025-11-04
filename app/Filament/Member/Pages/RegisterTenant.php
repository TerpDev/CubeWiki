<?php
//namespace App\Filament\Member\Pages;
//
//use App\Models\Tenants;
//use Filament\Forms\Components\TextInput;
//use Filament\Forms\Components\Checkbox;
//use Filament\Pages\Tenancy\RegisterTenant as BaseRegisterTenant;
//use Filament\Notifications\Notification;
//use Illuminate\Validation\ValidationException;
//
//class RegisterTenant extends BaseRegisterTenant
//{
//    public static function getLabel(): string
//    {
//        return __('Register tenant');
//    }
//    public function mount(): void
//    {
//        if ($token = session()->pull('just_created_token')) {
//            \Filament\Notifications\Notification::make()
//                ->title('API Token created')
//                ->body($token)
//                ->success()
//                ->send();
//        }
//    }
//
//    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
//    {
//        return $schema->schema([
//            TextInput::make('name')->label(__('Name'))->required()->maxLength(255),
//            Checkbox::make('join_if_exists')
//                ->label(__('Join tenant if the name already exists'))
//                ->default(true),
//        ]);
//    }
//
//    protected function handleRegistration(array $data): Tenants
//    {
//        $existing = Tenants::where('name', $data['name'])->first();
//
//        if ($existing) {
//            if (!empty($data['join_if_exists'])) {
//                auth()->user()->tenants()->attach($existing->id);
//
//                Notification::make()
//                    ->success()
//                    ->title(__('Joined tenant'))
//                    ->body(__('You have been added to: :name', ['name' => $existing->name]))
//                    ->send();
//
//                return $existing;
//            }
//
//            Notification::make()
//                ->danger()
//                ->title(__('Tenant name taken'))
//                ->body(__('A tenant with this name already exists. Enable the checkbox if you want to join this tenant, or choose a different name.'))
//                ->send();
//
//            throw ValidationException::withMessages([
//                'name' => __('A tenant with this name already exists. Enable "Join existing tenant if the name already exists" to join it, or choose a different name.'),
//            ]);
//        }
//
//        /** @var Tenants $tenant */
//        $tenant = Tenants::create(['name' => $data['name']]);
//        auth()->user()->tenants()->attach($tenant->id);
//
//        Notification::make()
//            ->success()
//            ->title(__('Tenant created'))
//            ->body(__('Tenant created and you have been added to: :name', ['name' => $tenant->name]))
//            ->send();
//
//        return $tenant;
//    }
//}

namespace App\Filament\Member\Pages;

use App\Models\Tenants;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Pages\Tenancy\RegisterTenant as BaseRegisterTenant;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class RegisterTenant extends BaseRegisterTenant
{
    public static function getLabel(): string
    {
        return __('Register tenant');
    }

    public function mount(): void
    {
        parent::mount();
        // (optional) if you prefer to always create/show on page load:
        // $this->ensureTokenForAuthAndNotify();
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema->schema([
            TextInput::make('name')->label(__('Name'))->required()->maxLength(255),
            Checkbox::make('join_if_exists')
                ->label(__('Join tenant if the name already exists'))
                ->default(true),
        ]);
    }

    protected function handleRegistration(array $data): Tenants
    {
        $existing = Tenants::where('name', $data['name'])->first();

        if ($existing) {
            if (!empty($data['join_if_exists'])) {
                auth()->user()->tenants()->attach($existing->id);

                // 🔑 make + show token here
                $this->ensureTokenForAuthAndNotify();

                Notification::make()
                    ->success()
                    ->title(__('Joined tenant'))
                    ->body(__('You have been added to: :name', ['name' => $existing->name]))
                    ->send();

                return $existing;
            }

            Notification::make()
                ->danger()
                ->title(__('Tenant name taken'))
                ->body(__('A tenant with this name already exists. Enable the checkbox if you want to join this tenant, or choose a different name.'))
                ->send();

            throw ValidationException::withMessages([
                'name' => __('A tenant with this name already exists. Enable "Join existing tenant if the name already exists" to join it, or choose a different name.'),
            ]);
        }

        /** @var Tenants $tenant */
        $tenant = Tenants::create(['name' => $data['name']]);
        auth()->user()->tenants()->attach($tenant->id);

        // 🔑 make + show token here
        $this->ensureTokenForAuthAndNotify();

        Notification::make()
            ->success()
            ->title(__('Tenant created'))
            ->body(__('Tenant created and you have been added to: :name', ['name' => $tenant->name]))
            ->send();

        return $tenant;
    }

    private function ensureTokenForAuthAndNotify(): void
    {
        $user = auth()->user();
        if (!$user) return;

        // create only if none (remove this if you want a new token every time)
        if (!$user->tokens()->exists()) {
            $token = $user->createToken('default')->plainTextToken;

            Notification::make()
                ->title('API Token created')
                ->body($token) // only shown once; it won’t be retrievable later
                ->success()
                ->send();
        }
    }
}
