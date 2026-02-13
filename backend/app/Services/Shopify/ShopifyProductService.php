<?php

namespace App\Services\Shopify;

use App\Contracts\Shopify\ShopifyApiInterface;
use App\Contracts\Shopify\ShopifyProductApiInterface;

class ShopifyProductService implements ShopifyProductApiInterface
{
    public function __construct(
        private readonly ShopifyApiInterface $apiClient
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
        $response = $this->apiClient->get('/products/count.json');

        return $response['count'] ?? 0;
    }
}
