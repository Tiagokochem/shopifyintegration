<?php

namespace App\Services\Shopify;

use App\Models\Product;

/**
 * Shopify Product Formatter
 * 
 * Formats product data for Shopify API requests.
 * Centralizes the formatting logic to avoid duplication.
 * 
 * Demonstrates: Single Responsibility Principle, DRY (Don't Repeat Yourself)
 */
class ShopifyProductFormatter
{
    /**
     * Format product data for Shopify API
     *
     * @param array|Product $productData Product data array or Product model instance
     * @return array Formatted data for Shopify API
     */
    public function format(array|Product $productData): array
    {
        // Handle Product model instance
        if ($productData instanceof Product) {
            $productData = [
                'title' => $productData->title,
                'description' => $productData->description,
                'price' => $productData->price,
                'vendor' => $productData->vendor,
                'product_type' => $productData->product_type,
                'status' => $productData->status,
            ];
        }

        $shopifyProduct = [
            'title' => $productData['title'] ?? '',
            'body_html' => $productData['description'] ?? '',
            'vendor' => $productData['vendor'] ?? null,
            'product_type' => $productData['product_type'] ?? null,
            'status' => $productData['status'] ?? 'active',
        ];

        // Add variant with price
        if (isset($productData['price'])) {
            $shopifyProduct['variants'] = [
                [
                    'price' => (string) $productData['price'],
                    'inventory_management' => 'shopify',
                ],
            ];
        }

        return $shopifyProduct;
    }
}
