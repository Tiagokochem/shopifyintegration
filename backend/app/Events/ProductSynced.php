<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Product Synced Event
 * 
 * Fired when a product is successfully synced from Shopify.
 * This event allows other parts of the system to react to product syncs
 * without tight coupling (Observer Pattern).
 * 
 * Demonstrates: Observer Pattern, Event-Driven Architecture
 */
class ProductSynced
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Product $product,
        public readonly string $action, // 'created' or 'updated'
        public readonly array $shopifyData
    ) {
    }
}
