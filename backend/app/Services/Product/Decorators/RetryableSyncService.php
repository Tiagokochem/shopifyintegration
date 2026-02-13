<?php

namespace App\Services\Product\Decorators;

use App\Contracts\Shopify\ShopifyProductApiInterface;
use App\Services\Product\ProductSyncService;

/**
 * Retryable Sync Service Decorator
 * 
 * Adds retry logic with exponential backoff to the sync service
 * without modifying the original implementation.
 * 
 * Demonstrates: Decorator Pattern, Open/Closed Principle
 */
class RetryableSyncService extends ProductSyncService
{
    private const MAX_RETRIES = 3;
    private const INITIAL_DELAY = 1; // seconds

    public function syncProducts(int $limit = 250): array
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < self::MAX_RETRIES) {
            try {
                return parent::syncProducts($limit);
            } catch (\Exception $e) {
                $lastException = $e;
                $attempt++;

                if ($attempt < self::MAX_RETRIES) {
                    $delay = self::INITIAL_DELAY * (2 ** ($attempt - 1)); // Exponential backoff
                    sleep($delay);
                }
            }
        }

        throw $lastException;
    }
}
