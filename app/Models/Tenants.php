<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Category;
use App\Models\Application;
use App\Models\Page;
use App\Models\User;

class Tenants extends Model
{
    use HasSlug;

    protected $fillable = ['name','slug'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(64)
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Explicit pivot keys: tenant_id is the pivot key on tenant_users for this model
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'tenant_users',
            'tenant_id',
            'user_id'
        )->withTimestamps();
    }
    public function categories(): HasMany { return $this->hasMany(Category::class, 'tenant_id', 'id'); }
    public function applications(): HasMany { return $this->hasMany(Application::class, 'tenant_id', 'id'); }
    public function pages(): HasMany { return $this->hasMany(Page::class, 'tenant_id', 'id'); }
}
