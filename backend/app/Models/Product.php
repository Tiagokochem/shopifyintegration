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
        'synced_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'synced_at' => 'datetime',
    ];

    protected $dates = [
        'synced_at',
    ];
}
