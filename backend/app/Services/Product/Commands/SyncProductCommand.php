<?php

namespace App\Services\Product\Commands;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Product\SyncProductCommandInterface;
use App\Events\ProductSynced;
use App\Events\ProductSyncFailed;
use App\Models\Product;

/**
 * Sync Product Command
 * 
 * Encapsulates the logic for syncing a single product.
 * This command can be executed, queued, or composed with other commands.
 * 
 * Demonstrates: Command Pattern, Single Responsibility Principle
 */
class SyncProductCommand implements SyncProductCommandInterface
{
    public function __construct(
        private readonly array $shopifyProduct,
        private readonly ProductRepositoryInterface $repository,
        private readonly ProductSyncStrategyInterface $strategy
    ) {
    }

    public function execute(): string
    {
        $shopifyId = (string) ($this->shopifyProduct['id'] ?? '');

        if (empty($shopifyId)) {
            return 'skipped';
        }

        try {
            $existingProduct = $this->repository->findByShopifyId($shopifyId);

            if (!$existingProduct && $this->strategy->shouldCreate($this->shopifyProduct)) {
                return $this->createProduct();
            }

            if ($existingProduct && $this->strategy->shouldUpdate($existingProduct, $this->shopifyProduct)) {
                return $this->updateProduct($existingProduct);
            }

            return 'skipped';
        } catch (\Exception $e) {
            event(new ProductSyncFailed($shopifyId, $e, $this->shopifyProduct));
            throw $e;
        }
    }

    public function getShopifyProduct(): array
    {
        return $this->shopifyProduct;
    }

    private function createProduct(): string
    {
        $data = $this->strategy->transformProductData($this->shopifyProduct);
        $product = $this->repository->create($data);
        
        event(new ProductSynced($product, 'created', $this->shopifyProduct));
        
        return 'created';
    }

    private function updateProduct(Product $existingProduct): string
    {
        $data = $this->strategy->transformProductData($this->shopifyProduct);
        $product = $this->repository->update($existingProduct, $data);
        
        event(new ProductSynced($product, 'updated', $this->shopifyProduct));
        
        return 'updated';
    }
}
