<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Product Updated Event
 * 
 * Fired when a product is updated locally.
 * Allows other parts of the system to react (Observer Pattern).
 */
class ProductUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Product $product
    ) {
    }
}
