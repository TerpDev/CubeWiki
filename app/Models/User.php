<?php

// App\Models\User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Collection;

// Filament contracts:
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use function Laravel\Prompts\alert;

class User extends Authenticatable implements FilamentUser, HasTenants, HasDefaultTenant
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = ['name','email','password'];
    protected $hidden = ['password','two_factor_secret','two_factor_recovery_codes','remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function initials(): string
    {
        return Str::of($this->name)->explode(' ')->take(2)->map(fn($w) => Str::substr($w,0,1))->implode('');
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenants::class, 'tenant_users', 'user_id', 'tenant_id')->withTimestamps();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->email === 'admin@admin.com';
        }

        return true;
    }

    public function canAccessTenant(\Illuminate\Database\Eloquent\Model $tenant): bool
    {
        return $this->tenants()->where('tenants.id', $tenant->id)->exists();
    }

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->tenants()->get();
    }

    public function getDefaultTenant(Panel $panel): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->tenants()->first();
    }

}

