<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Shopify\ShopifyProductApiInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductSyncService
{
    public function __construct(
        private readonly ShopifyProductApiInterface $shopifyProductApi,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductSyncStrategyInterface $syncStrategy,
        private readonly ProductTransformer $transformer
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

            foreach ($shopifyProducts as $shopifyProduct) {
                try {
                    $result = $this->syncSingleProduct($shopifyProduct);
                    $stats[$result]++;
                } catch (\Exception $e) {
                    Log::error('Failed to sync product', [
                        'shopify_id' => $shopifyProduct['id'] ?? 'unknown',
                        'error' => $e->getMessage(),
                    ]);
                    $stats['errors']++;
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch products from Shopify', [
                'error' => $e->getMessage(),
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
            $this->productRepository->create($data);

            return 'created';
        }

        if ($existingProduct && $this->syncStrategy->shouldUpdate($existingProduct, $shopifyProduct)) {
            $data = $this->syncStrategy->transformProductData($shopifyProduct);
            $this->productRepository->update($existingProduct, $data);

            return 'updated';
        }

        return 'skipped';
    }
}
