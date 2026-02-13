<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Product Created Event
 * 
 * Fired when a product is created locally.
 * Allows other parts of the system to react (Observer Pattern).
 */
class ProductCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Product $product
    ) {
    }
}
