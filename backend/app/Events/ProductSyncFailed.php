<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Product Sync Failed Event
 * 
 * Fired when a product sync fails.
 * Allows error handling, logging, and notifications to be decoupled
 * from the sync service.
 * 
 * Demonstrates: Observer Pattern, Separation of Concerns
 */
class ProductSyncFailed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly string $shopifyId,
        public readonly \Throwable $exception,
        public readonly array $shopifyData
    ) {
    }
}
