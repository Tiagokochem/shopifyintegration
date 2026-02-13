<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Product\ProductTransformerInterface;
use App\Models\Product;

class DefaultProductSyncStrategy implements ProductSyncStrategyInterface
{
    public function __construct(
        private readonly ProductTransformerInterface $transformer
    ) {
    }

    public function shouldCreate(array $shopifyProduct): bool
    {
        // Always create if product doesn't exist
        return true;
    }

    public function shouldUpdate(Product $existingProduct, array $shopifyProduct): bool
    {
        // Update if synced_at is null or older than 1 hour
        if (!$existingProduct->synced_at) {
            return true;
        }

        // Update if product data has changed (simple comparison)
        $transformedData = $this->transformer->transform($shopifyProduct);

        return $existingProduct->title !== $transformedData['title']
            || $existingProduct->price != $transformedData['price']
            || $existingProduct->status !== $transformedData['status'];
    }

    public function transformProductData(array $shopifyProduct): array
    {
        return $this->transformer->transform($shopifyProduct);
    }
}
