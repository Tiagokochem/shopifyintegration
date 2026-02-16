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
