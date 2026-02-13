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
        // Versões válidas do Shopify API: 2024-01, 2024-04, 2024-07, 2023-10, etc
        // 2024-10 pode não existir ainda, usar 2024-01 como padrão seguro
        $this->apiVersion = config('shopify.api_version', '2024-01');

        if (!$storeUrl || !$this->accessToken) {
            throw new \RuntimeException('Shopify credentials are not configured');
        }

        // Garantir que a URL não tenha /admin/api já incluído
        $storeUrl = rtrim($storeUrl, '/');
        if (str_contains($storeUrl, '/admin/api')) {
            $storeUrl = preg_replace('/\/admin\/api\/.*$/', '', $storeUrl);
        }

        $this->baseUrl = $storeUrl . '/admin/api/' . $this->apiVersion;

        $this->client = new Client([
            'headers' => [
                'X-Shopify-Access-Token' => $this->accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
            'allow_redirects' => false,
            'http_errors' => false,
        ]);
    }

    public function get(string $endpoint, array $params = []): array
    {
        try {
            // Construir URL completa (como o curl que funcionou)
            $fullUrl = $this->baseUrl . '/' . ltrim($endpoint, '/');
            if (!empty($params)) {
                $fullUrl .= '?' . http_build_query($params);
            }


            // Usar URL completa diretamente (como curl fez)
            $response = $this->client->get($fullUrl);

            $body = $response->getBody()->getContents();
            $statusCode = $response->getStatusCode();

            // Verificar se é redirect (302, 301, etc)
            if ($statusCode >= 300 && $statusCode < 400) {
                $location = $response->getHeaderLine('Location');
                Log::error('Shopify API returned redirect', [
                    'endpoint' => $endpoint,
                    'full_url' => $fullUrl,
                    'status_code' => $statusCode,
                    'redirect_location' => $location,
                    'response_preview' => substr($body, 0, 500),
                ]);

                // Tentar encontrar versão válida se redireciona para /password
                $suggestedVersion = null;
                if (str_contains($location, '/password')) {
                    $storeUrl = config('shopify.store_url');
                    $accessToken = config('shopify.access_token');
                    $result = ShopifyApiVersionResolver::findValidVersion(
                        $storeUrl,
                        $accessToken,
                        $this->apiVersion
                    );
                    $suggestedVersion = $result['version'];
                }

                $errorMessage = "Shopify API returned redirect (status {$statusCode}).\n\n";
                $errorMessage .= "Possible causes:\n";
                $errorMessage .= "1. API version '{$this->apiVersion}' is invalid.\n";
                
                if ($suggestedVersion) {
                    $errorMessage .= "   ✅ Found valid version: '{$suggestedVersion}'\n";
                    $errorMessage .= "   💡 Update backend/.env: SHOPIFY_API_VERSION={$suggestedVersion}\n";
                } else {
                    $errorMessage .= "   Try: SHOPIFY_API_VERSION=2024-01 or 2024-04 or 2023-10\n";
                }
                
                $errorMessage .= "2. Access token is invalid or expired.\n";
                $errorMessage .= "   Verify token has 'read_products' permission.\n";
                $errorMessage .= "3. Store URL is incorrect.\n";
                $errorMessage .= "\nRedirect location: {$location}\n";
                $errorMessage .= "Full URL: {$fullUrl}";

                throw new \RuntimeException($errorMessage);
            }

            // Verificar status code de erro
            if ($statusCode >= 400) {
                Log::error('Shopify API returned error status', [
                    'endpoint' => $endpoint,
                    'full_url' => $fullUrl,
                    'status_code' => $statusCode,
                    'response_preview' => substr($body, 0, 1000),
                    'headers' => $response->getHeaders(),
                ]);

                throw new \RuntimeException(
                    "Shopify API returned status {$statusCode}. " .
                    "This usually means the access token is invalid or expired, or the API version is incorrect. " .
                    "Response: " . substr($body, 0, 200) .
                    ". Full URL: " . $fullUrl
                );
            }

            // Verificar se a resposta é HTML (erro)
            if (str_starts_with(trim($body), '<!DOCTYPE') || str_starts_with(trim($body), '<html')) {
                Log::error('Shopify API returned HTML instead of JSON', [
                    'endpoint' => $endpoint,
                    'full_url' => $fullUrl,
                    'status_code' => $statusCode,
                    'response_preview' => substr($body, 0, 1000),
                ]);

                throw new \RuntimeException(
                    'Shopify API returned HTML instead of JSON. ' .
                    'This usually means the URL is incorrect or the access token is invalid. ' .
                    'Check if your access token has the correct permissions (read_products). ' .
                    'Full URL: ' . $fullUrl
                );
            }

            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Shopify API response is not valid JSON', [
                    'endpoint' => $endpoint,
                    'full_url' => $fullUrl,
                    'status_code' => $statusCode,
                    'json_error' => json_last_error_msg(),
                    'response_preview' => substr($body, 0, 500),
                ]);

                throw new \RuntimeException(
                    'Shopify API response is not valid JSON: ' . json_last_error_msg() .
                    '. Full URL: ' . $fullUrl
                );
            }

            return $data ?? [];
        } catch (GuzzleException $e) {
            $statusCode = $e->hasResponse() ? $e->getResponse()->getStatusCode() : null;
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null;

            Log::error('Shopify API GET request failed', [
                'endpoint' => $endpoint,
                'params' => $params,
                'status_code' => $statusCode,
                'error' => $e->getMessage(),
                'response_body' => $responseBody,
            ]);

            throw new \RuntimeException('Shopify API request failed: ' . $e->getMessage(), 0, $e);
        }
    }


    public function post(string $endpoint, array $data = []): array
    {
        try {
            $fullUrl = $this->baseUrl . '/' . ltrim($endpoint, '/');
            $response = $this->client->post($fullUrl, [
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
            $fullUrl = $this->baseUrl . '/' . ltrim($endpoint, '/');
            $response = $this->client->put($fullUrl, [
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
            $fullUrl = $this->baseUrl . '/' . ltrim($endpoint, '/');
            $response = $this->client->delete($fullUrl);

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
