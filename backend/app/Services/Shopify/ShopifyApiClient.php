<?php

namespace App\Services\Shopify;

use App\Contracts\Shopify\ShopifyApiInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ShopifyApiClient implements ShopifyApiInterface
{
    private Client $client;
    private string $baseUrl;
    private string $accessToken;
    private string $apiVersion;

    public function __construct()
    {
        $storeUrl = config('shopify.store_url');
        $this->accessToken = config('shopify.access_token');
        $this->apiVersion = config('shopify.api_version', '2024-10');

        if (!$storeUrl || !$this->accessToken) {
            throw new \RuntimeException('Shopify credentials are not configured');
        }

        $this->baseUrl = rtrim($storeUrl, '/') . '/admin/api/' . $this->apiVersion;

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'X-Shopify-Access-Token' => $this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
        ]);
    }

    public function get(string $endpoint, array $params = []): array
    {
        try {
            $response = $this->client->get($endpoint, [
                'query' => $params,
            ]);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            Log::error('Shopify API GET request failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            throw new \RuntimeException('Shopify API request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function post(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->client->post($endpoint, [
                'json' => $data,
            ]);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            Log::error('Shopify API POST request failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            throw new \RuntimeException('Shopify API request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function put(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->client->put($endpoint, [
                'json' => $data,
            ]);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            Log::error('Shopify API PUT request failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            throw new \RuntimeException('Shopify API request failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public function delete(string $endpoint): array
    {
        try {
            $response = $this->client->delete($endpoint);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            Log::error('Shopify API DELETE request failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            throw new \RuntimeException('Shopify API request failed: ' . $e->getMessage(), 0, $e);
        }
    }
}
