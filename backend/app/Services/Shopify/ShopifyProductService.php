<?php

namespace App\Services\Shopify;

use App\Contracts\Shopify\ShopifyApiInterface;
use App\Contracts\Shopify\ShopifyProductApiInterface;

class ShopifyProductService implements ShopifyProductApiInterface
{
    public function __construct(
        private readonly ShopifyApiInterface $apiClient,
        private readonly ShopifyProductFormatter $formatter
    ) {
    }

    public function getProducts(int $limit = 250, ?string $sinceId = null): array
    {
        $params = ['limit' => $limit];

        if ($sinceId) {
            $params['since_id'] = $sinceId;
        }

        $response = $this->apiClient->get('/products.json', $params);

        return $response['products'] ?? [];
    }

    public function getProduct(string $shopifyId): ?array
    {
        try {
            $response = $this->apiClient->get("/products/{$shopifyId}.json");

            return $response['product'] ?? null;
        } catch (\RuntimeException $e) {
            if (str_contains($e->getMessage(), '404')) {
                return null;
            }

            throw $e;
        }
    }

    public function getProductsCount(): int
    {
        $response = $this->apiClient->get('/products/count.json', []);

        return $response['count'] ?? 0;
    }

    public function createProduct(array $productData): array
    {
        $shopifyProduct = $this->formatter->format($productData);
        
        $response = $this->apiClient->post('/products.json', [
            'product' => $shopifyProduct,
        ]);

        return $response['product'] ?? [];
    }

    public function updateProduct(string $shopifyId, array $productData): array
    {
        $shopifyProduct = $this->formatter->format($productData);
        
        $response = $this->apiClient->put("/products/{$shopifyId}.json", [
            'product' => $shopifyProduct,
        ]);

        return $response['product'] ?? [];
    }

    public function deleteProduct(string $shopifyId): bool
    {
        try {
            $this->apiClient->delete("/products/{$shopifyId}.json");
            return true;
        } catch (\RuntimeException $e) {
            if (str_contains($e->getMessage(), '404')) {
                return false;
            }
            throw $e;
        }
    }

}
