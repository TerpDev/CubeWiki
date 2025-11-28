<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasDefaultTenant;
use Filament\Models\Contracts\HasTenants;
use Filament\Panel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 */
class User extends Authenticatable implements FilamentUser, HasDefaultTenant, HasTenants
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function initials(): string
    {
        return Str::of($this->name)->explode(' ')->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode('');
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

    /**
     * Narrowed in phpdoc for static analysis to the Tenants model,
     * but keep the runtime signature compatible with the HasTenants contract.
     *
     * @param Tenants|Model $tenant
     */
    public function canAccessTenant(Model $tenant): bool
    {
        /** @var Tenants $tenant */
        // use getKey() to avoid relying on dynamic ->id on a generic Model
        return $this->tenants()->where('tenants.id', $tenant->getKey())->exists();
    }

    public function getTenants(Panel $panel): array|Collection
    {
        return $this->tenants()->get();
    }

    /**
     * Keep the return type compatible with HasDefaultTenant/HasTenants contract,
     * but narrow the phpdoc return for static analyzers.
     *
     * @return Tenants|null
     */
    public function getDefaultTenant(Panel $panel): ?Model
    {
        /** @var Tenants|null $tenant */
        $tenant = $this->tenants()->first();

        return $tenant;
    }
}
