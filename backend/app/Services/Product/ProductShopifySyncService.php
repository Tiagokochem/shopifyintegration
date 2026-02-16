<?php

namespace App\Services\Product;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Shopify\ShopifyProductApiInterface;
use App\Models\Product;
use App\Services\Shopify\ShopifyProductFormatter;
use Illuminate\Support\Facades\Log;

/**
 * Product Shopify Sync Service
 * 
 * Handles synchronization of products TO Shopify (push operations).
 * This service is responsible for creating, updating, and deleting products
 * in Shopify when they are modified locally.
 * 
 * Demonstrates: Single Responsibility Principle, Dependency Inversion Principle
 */
class ProductShopifySyncService
{
    public function __construct(
        private readonly ShopifyProductApiInterface $shopifyProductApi,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ShopifyProductFormatter $formatter
    ) {
    }

    /**
     * Sync a product to Shopify (create or update)
     *
     * @param Product $product
     * @return Product
     */
    public function syncToShopify(Product $product): Product
    {
        try {
            // If updating and price is missing/invalid, fetch current product from Shopify
            // to preserve existing price and description
            if ($product->shopify_id) {
                $currentShopifyProduct = $this->shopifyProductApi->getProduct($product->shopify_id);
                
                // If local price is 0 or null, use Shopify's current price
                if (($product->price === null || (float) $product->price <= 0) && $currentShopifyProduct) {
                    $variants = $currentShopifyProduct['variants'] ?? [];
                    $firstVariant = $variants[0] ?? [];
                    if (isset($firstVariant['price'])) {
                        $product->price = (float) $firstVariant['price'];
                        Log::info('Using Shopify current price for product', [
                            'product_id' => $product->id,
                            'price' => $product->price,
                        ]);
                    }
                }
                
                // If local description is empty, use Shopify's current description
                if (empty(trim($product->description ?? '')) && $currentShopifyProduct) {
                    $currentDescription = $currentShopifyProduct['body_html'] ?? '';
                    if (!empty(trim($currentDescription))) {
                        $product->description = $currentDescription;
                        Log::info('Using Shopify current description for product', [
                            'product_id' => $product->id,
                        ]);
                    }
                }
            }
            
            // Ensure product is fresh and get price value
            $product->refresh();
            
            // Get price value - handle decimal cast properly
            $price = $product->price;
            if ($price !== null) {
                $price = is_string($price) ? (float) $price : (float) $price;
            }
            
            // Validate price - if invalid, try to get from Shopify
            if ($price === null || $price <= 0) {
                if ($product->shopify_id && isset($currentShopifyProduct)) {
                    $variants = $currentShopifyProduct['variants'] ?? [];
                    $firstVariant = $variants[0] ?? [];
                    if (isset($firstVariant['price'])) {
                        $price = (float) $firstVariant['price'];
                        // Update local product with Shopify price
                        $this->productRepository->update($product, ['price' => $price]);
                        $product->refresh();
                        Log::info('Updated product price from Shopify', [
                            'product_id' => $product->id,
                            'price' => $price,
                        ]);
                    }
                }
                
                // Final validation
                if ($price === null || $price <= 0) {
                    throw new \InvalidArgumentException(
                        'Product price is required and must be greater than 0 when syncing to Shopify. ' .
                        'Product ID: ' . $product->id . ', Current price: ' . ($product->price ?? 'null')
                    );
                }
            }

            $productData = $this->formatter->format($product);

            if ($product->shopify_id) {
                // Update existing product in Shopify
                $shopifyProduct = $this->shopifyProductApi->updateProduct(
                    $product->shopify_id,
                    $productData
                );
            } else {
                // Create new product in Shopify
                $shopifyProduct = $this->shopifyProductApi->createProduct($productData);
                
                // Update local product with Shopify ID
                $this->productRepository->update($product, [
                    'shopify_id' => (string) $shopifyProduct['id'],
                ]);
                
                $product->refresh();
            }

            // Update synced_at timestamp
            $this->productRepository->update($product, [
                'synced_at' => now(),
            ]);

            return $product->fresh();
        } catch (\Exception $e) {
            Log::error('Failed to sync product to Shopify', [
                'product_id' => $product->id,
                'shopify_id' => $product->shopify_id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Delete product from Shopify
     *
     * @param Product $product
     * @return bool
     */
    public function deleteFromShopify(Product $product): bool
    {
        if (!$product->shopify_id) {
            return true; // Product was never synced to Shopify
        }

        try {
            $this->shopifyProductApi->deleteProduct($product->shopify_id);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete product from Shopify', [
                'product_id' => $product->id,
                'shopify_id' => $product->shopify_id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
