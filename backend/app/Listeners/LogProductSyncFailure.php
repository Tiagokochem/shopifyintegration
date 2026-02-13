<?php

namespace App\Listeners;

use App\Events\ProductSyncFailed;
use Illuminate\Support\Facades\Log;

/**
 * Log Product Sync Failure Listener
 * 
 * Logs product sync failures for monitoring and debugging.
 * 
 * Demonstrates: Observer Pattern, Single Responsibility Principle
 */
class LogProductSyncFailure
{
    /**
     * Handle the event.
     */
    public function handle(ProductSyncFailed $event): void
    {
        Log::error('Product sync failed', [
            'shopify_id' => $event->shopifyId,
            'error' => $event->exception->getMessage(),
            'trace' => $event->exception->getTraceAsString(),
        ]);
    }
}
