<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Shopify\ShopifyProductApiInterface;
use App\Events\ProductSynced;
use App\Events\ProductSyncFailed;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductSyncService
{
    public function __construct(
        private readonly ShopifyProductApiInterface $shopifyProductApi,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductSyncStrategyInterface $syncStrategy
    ) {
    }

    /**
     * Sync products from Shopify
     *
     * @param int $limit
     * @return array Statistics about the sync operation
     */
    public function syncProducts(int $limit = 250): array
    {
        $stats = [
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => 0,
        ];

        try {
            $shopifyProducts = $this->shopifyProductApi->getProducts($limit);

            if (empty($shopifyProducts)) {
                return $stats;
            }

            foreach ($shopifyProducts as $shopifyProduct) {
                try {
                    $result = $this->syncSingleProduct($shopifyProduct);
                    $stats[$result]++;
                } catch (\Exception $e) {
                    // Dispatch event for error handling (Observer Pattern)
                    event(new ProductSyncFailed(
                        (string) ($shopifyProduct['id'] ?? 'unknown'),
                        $e,
                        $shopifyProduct
                    ));
                    $stats['errors']++;
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch products from Shopify', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }

        return $stats;
    }

    /**
     * Sync a single product
     *
     * @param array $shopifyProduct
     * @return string Action taken: 'created', 'updated', or 'skipped'
     */
    private function syncSingleProduct(array $shopifyProduct): string
    {
        $shopifyId = (string) ($shopifyProduct['id'] ?? '');

        if (empty($shopifyId)) {
            return 'skipped';
        }

        $existingProduct = $this->productRepository->findByShopifyId($shopifyId);

        if (!$existingProduct && $this->syncStrategy->shouldCreate($shopifyProduct)) {
            $data = $this->syncStrategy->transformProductData($shopifyProduct);
            $product = $this->productRepository->create($data);
            
            // Dispatch event (Observer Pattern - decouples actions from business logic)
            event(new ProductSynced($product, 'created', $shopifyProduct));
            
            return 'created';
        }

        if ($existingProduct && $this->syncStrategy->shouldUpdate($existingProduct, $shopifyProduct)) {
            $data = $this->syncStrategy->transformProductData($shopifyProduct);
            $product = $this->productRepository->update($existingProduct, $data);
            
            // Dispatch event (Observer Pattern)
            event(new ProductSynced($product, 'updated', $shopifyProduct));
            
            return 'updated';
        }

        return 'skipped';
    }
}
