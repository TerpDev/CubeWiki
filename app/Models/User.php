<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};
use Illuminate\Database\Eloquent\Collection;
use Filament\Panel;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // Define the many-to-many relationship between users and tenants using the tenant_users pivot table
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenants::class, 'tenant_users', 'user_id', 'tenant_id')->withTimestamps();
    }

    // Check whether this user is attached to the given tenant
    public function canAccessTenant(Tenants $tenant): bool
    {
        return $this->tenants()->where('tenants.id', $tenant->id)->exists();
    }

    // Return the collection of tenants for this user. Panel param is optional for Filament compatibility.
    public function getTenants(?Panel $panel = null): Collection
    {
        return $this->tenants()->get();
    }

    // Return a default tenant (first attached) — Panel param is optional.
    public function getDefaultTenant(?Panel $panel = null): ?Tenants
    {
        return $this->tenants()->first();
    }

}
