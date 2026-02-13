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

    public function createProduct(array $productData): array
    {
        // Formatar dados para o formato esperado pela API do Shopify
        $shopifyProduct = $this->formatProductForShopify($productData);
        
        $response = $this->apiClient->post('/products.json', [
            'product' => $shopifyProduct,
        ]);

        return $response['product'] ?? [];
    }

    public function updateProduct(string $shopifyId, array $productData): array
    {
        // Formatar dados para o formato esperado pela API do Shopify
        $shopifyProduct = $this->formatProductForShopify($productData);
        
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
                return false; // Produto já não existe
            }
            throw $e;
        }
    }

    /**
     * Format product data to Shopify API format
     *
     * @param array $productData
     * @return array
     */
    private function formatProductForShopify(array $productData): array
    {
        $shopifyProduct = [
            'title' => $productData['title'] ?? '',
            'body_html' => $productData['description'] ?? '',
            'vendor' => $productData['vendor'] ?? null,
            'product_type' => $productData['product_type'] ?? null,
            'status' => $productData['status'] ?? 'active',
        ];

        // Adicionar variante com preço
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
