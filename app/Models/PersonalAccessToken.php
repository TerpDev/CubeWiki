<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
        'application_id',
        'category_id',
        'page_id',
    ];

    /**
     * Get the application that this token is associated with.
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the category that this token is associated with.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the page that this token is associated with.
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
