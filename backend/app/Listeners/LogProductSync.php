<?php

namespace App\Listeners;

use App\Events\ProductSynced;
use Illuminate\Support\Facades\Log;

/**
 * Log Product Sync Listener
 * 
 * Logs product sync events for audit purposes.
 * This demonstrates how actions can be decoupled from business logic
 * using the Observer Pattern.
 * 
 * Demonstrates: Observer Pattern, Single Responsibility Principle
 */
class LogProductSync
{
    /**
     * Handle the event.
     */
    public function handle(ProductSynced $event): void
    {
        Log::info('Product synced', [
            'product_id' => $event->product->id,
            'shopify_id' => $event->product->shopify_id,
            'action' => $event->action,
            'title' => $event->product->title,
        ]);
    }
}
