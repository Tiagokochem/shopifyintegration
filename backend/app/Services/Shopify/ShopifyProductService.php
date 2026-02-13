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

        // Não usar status=any por padrão - pode causar problemas na API
        // Primeiro tentar sem status (retorna apenas produtos ativos/publicados)
        // Se precisar incluir drafts/archived, pode ser feito depois
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
        // Não usar status=any - pode causar problemas na API
        // Buscar contagem sem filtro de status (retorna apenas produtos ativos/publicados)
        $response = $this->apiClient->get('/products/count.json', []);

        return $response['count'] ?? 0;
    }
}
