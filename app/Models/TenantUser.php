<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class TenantUser extends Model
{
    protected $table = 'tenant_users';
    protected $fillable = [
        'tenant_id',
        'user_id',
    ];
    public function tenant(): BelongsTo
    {
        // explicit foreign key on tenant_users is tenant_id
        return $this->belongsTo(Tenants::class, 'tenant_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
