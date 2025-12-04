<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model
{
    use HasSlug;

    // ensure the table is lowercase plural
    protected $table = 'pages';

    protected $fillable = ['tenant_id', 'category_id', 'title', 'slug', 'content'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(80)
            ->doNotGenerateSlugsOnUpdate();
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenants::class, 'tenant_id', 'id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    protected static function booted(): void
    {
        static::saving(function (Page $page): void {
            if ($page->category_id && ! $page->tenant_id) {
                $page->tenant_id = Category::query()->whereKey($page->category_id)->value('tenant_id');
            }
        });
    }
}
