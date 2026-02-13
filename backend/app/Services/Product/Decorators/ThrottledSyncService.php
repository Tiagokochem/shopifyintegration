<?php

namespace App\Services\Product\Decorators;

use App\Services\Product\ProductSyncService;

/**
 * Throttled Sync Service Decorator
 * 
 * Adds rate limiting to the sync service to respect API limits
 * without modifying the original implementation.
 * 
 * Demonstrates: Decorator Pattern, Open/Closed Principle
 */
class ThrottledSyncService extends ProductSyncService
{
    private const REQUESTS_PER_SECOND = 2; // Shopify allows 2 requests per second
    private float $lastRequestTime = 0;

    public function syncProducts(int $limit = 250): array
    {
        $this->throttle();
        return parent::syncProducts($limit);
    }

    /**
     * Throttle requests to respect rate limits
     */
    private function throttle(): void
    {
        $currentTime = microtime(true);
        $timeSinceLastRequest = $currentTime - $this->lastRequestTime;
        $minInterval = 1.0 / self::REQUESTS_PER_SECOND;

        if ($timeSinceLastRequest < $minInterval) {
            $sleepTime = ($minInterval - $timeSinceLastRequest) * 1000000; // Convert to microseconds
            usleep((int) $sleepTime);
        }

        $this->lastRequestTime = microtime(true);
    }
}
