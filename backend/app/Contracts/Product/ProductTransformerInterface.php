<?php

namespace App\Contracts\Product;

/**
 * Product Transformer Interface
 * 
 * Defines contract for transforming Shopify product data to internal format.
 * This allows different transformation strategies without coupling to implementation.
 * 
 * Demonstrates: Dependency Inversion Principle, Interface Segregation Principle
 */
interface ProductTransformerInterface
{
    /**
     * Transform Shopify product data to internal format
     *
     * @param array $shopifyProduct
     * @return array
     */
    public function transform(array $shopifyProduct): array;
}
