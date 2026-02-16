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
            Log::warning('Product skipped: empty shopify_id', ['product' => $shopifyProduct]);
            return 'skipped';
        }

        $existingProduct = $this->productRepository->findByShopifyId($shopifyId);
        
        // Log for debugging
        Log::debug('Syncing product', [
            'shopify_id' => $shopifyId,
            'exists_in_db' => $existingProduct !== null,
            'strategy' => get_class($this->syncStrategy),
            'product_title' => $shopifyProduct['title'] ?? 'unknown',
            'product_status' => $shopifyProduct['status'] ?? 'unknown',
        ]);

        // If product doesn't exist, create it
        if (!$existingProduct) {
            $shouldCreate = $this->syncStrategy->shouldCreate($shopifyProduct);
            
            Log::info('Checking if product should be created', [
                'shopify_id' => $shopifyId,
                'should_create' => $shouldCreate,
                'strategy' => get_class($this->syncStrategy),
                'product_status' => $shopifyProduct['status'] ?? 'unknown',
                'product_title' => $shopifyProduct['title'] ?? 'unknown',
            ]);
            
            if ($shouldCreate) {
                try {
                    $data = $this->syncStrategy->transformProductData($shopifyProduct);
                    
                    // Ensure synced_at is set
                    if (!isset($data['synced_at'])) {
                        $data['synced_at'] = now();
                    }
                    
                    // Ensure sync_auto is set
                    if (!isset($data['sync_auto'])) {
                        $data['sync_auto'] = false;
                    }
                    
                    $product = $this->productRepository->create($data);
                    
                    // Dispatch event (Observer Pattern - decouples actions from business logic)
                    event(new ProductSynced($product, 'created', $shopifyProduct));
                    
                    Log::info('Product created from Shopify', [
                        'shopify_id' => $shopifyId,
                        'product_id' => $product->id,
                        'title' => $product->title,
                    ]);
                    
                    return 'created';
                } catch (\Exception $e) {
                    Log::error('Failed to create product from Shopify', [
                        'shopify_id' => $shopifyId,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'data' => $data ?? null,
                    ]);
                    throw $e;
                }
            } else {
                Log::warning('Product skipped: shouldCreate returned false', [
                    'shopify_id' => $shopifyId,
                    'strategy' => get_class($this->syncStrategy),
                    'product_status' => $shopifyProduct['status'] ?? 'unknown',
                    'product_title' => $shopifyProduct['title'] ?? 'unknown',
                ]);
                return 'skipped';
            }
        }

        // If product exists, update it
        if ($existingProduct) {
            if ($this->syncStrategy->shouldUpdate($existingProduct, $shopifyProduct)) {
                try {
                    $data = $this->syncStrategy->transformProductData($shopifyProduct);
                    
                    // Ensure synced_at is updated
                    $data['synced_at'] = now();
                    
                    $product = $this->productRepository->update($existingProduct, $data);
                    
                    // Dispatch event (Observer Pattern)
                    event(new ProductSynced($product, 'updated', $shopifyProduct));
                    
                    Log::info('Product updated from Shopify', [
                        'shopify_id' => $shopifyId,
                        'product_id' => $product->id,
                        'title' => $product->title,
                    ]);
                    
                    return 'updated';
                } catch (\Exception $e) {
                    Log::error('Failed to update product from Shopify', [
                        'shopify_id' => $shopifyId,
                        'product_id' => $existingProduct->id,
                        'error' => $e->getMessage(),
                    ]);
                    throw $e;
                }
            } else {
                Log::warning('Product skipped: shouldUpdate returned false', [
                    'shopify_id' => $shopifyId,
                    'product_id' => $existingProduct->id,
                ]);
                return 'skipped';
            }
        }

        // This should never be reached, but just in case
        Log::warning('Product skipped: unexpected condition', [
            'shopify_id' => $shopifyId,
            'existing_product' => $existingProduct ? $existingProduct->id : null,
        ]);
        return 'skipped';
    }
}
