<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopify_id',
        'title',
        'description',
        'price',
        'vendor',
        'product_type',
        'status',
        'sync_auto',
        'synced_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sync_auto' => 'boolean',
        'synced_at' => 'datetime',
    ];

    protected $dates = [
        'synced_at',
    ];

    /**
     * Get the sync_auto attribute, defaulting to false if null
     *
     * @param mixed $value
     * @return bool
     */
    public function getSyncAutoAttribute($value): bool
    {
        return (bool) $value;
    }
}
