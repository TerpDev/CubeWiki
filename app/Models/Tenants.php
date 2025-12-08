<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tenants extends Model
{
    use HasApiTokens, HasSlug;

    /**
     * @property int $id
     * @property string $name
     * @property string $slug
     */
    protected $table = 'tenants';

    protected $fillable = ['name', 'slug'];

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
        return $this->belongsToMany(User::class, 'tenant_users', 'tenant_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'tenant_id', 'id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'tenant_id', 'id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'tenant_id', 'id');
    }

    /**
     * Create a token for this tenant with optional resource IDs
     */
    public function createTokenWithResources(
        string $name,
        ?int $applicationId = null,
        ?int $categoryId = null,
        ?int $pageId = null,
        array $abilities = ['*']
    ) {
        $token = $this->createToken($name, $abilities);

        // Update the personal access token with resource IDs
        // NewAccessToken->accessToken is provided by Laravel\Sanctum, update directly
        $token->accessToken->update([
            'application_id' => $applicationId,
            'category_id' => $categoryId,
            'page_id' => $pageId,
        ]);

        return $token;
    }
}
