<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Category;

class Page extends Model
{
    use HasSlug;

    protected $fillable = ['tenant_id','category_id','title','slug','content'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(80)
        ->doNotGenerateSlugsOnUpdate();
    }

    // specify the foreign key explicitly to avoid Eloquent inferring 'tenants_id'
    public function tenant(): BelongsTo { return $this->belongsTo(Tenants::class, 'tenant_id', 'id'); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class, 'category_id', 'id'); }

    protected static function booted(): void
    {
        static::saving(function (Page $page) {
            if ($page->category_id) {
                $category = Category::find($page->category_id);
                if ($category) {
                    $page->tenant_id = $category->tenant_id;
                }
            }
        });
    }
}
