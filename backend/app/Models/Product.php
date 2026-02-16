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
        'handle',
        'title',
        'description',
        'price',
        'compare_at_price',
        'vendor',
        'product_type',
        'tags',
        'status',
        'sku',
        'weight',
        'weight_unit',
        'requires_shipping',
        'tracked',
        'inventory_quantity',
        'meta_title',
        'meta_description',
        'images',
        'featured_image',
        'template_suffix',
        'published',
        'published_at',
        'variants_data',
        'sync_auto',
        'synced_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'requires_shipping' => 'boolean',
        'tracked' => 'boolean',
        'published' => 'boolean',
        'sync_auto' => 'boolean',
        'images' => 'array',
        'variants_data' => 'array',
        'published_at' => 'datetime',
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
