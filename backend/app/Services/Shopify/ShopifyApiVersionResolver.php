<?php

namespace App\Services\Shopify;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ShopifyApiVersionResolver
{
    /**
     * Versões válidas do Shopify API (em ordem de preferência)
     * Formato: YYYY-MM (ano-mês)
     * Shopify lança novas versões trimestralmente (Janeiro, Abril, Julho, Outubro)
     */
    private const VALID_API_VERSIONS = [
        '2024-10', // Outubro 2024 (pode não estar disponível ainda)
        '2024-07', // Julho 2024
        '2024-04', // Abril 2024
        '2024-01', // Janeiro 2024
        '2023-10', // Outubro 2023
        '2023-07', // Julho 2023
        '2023-04', // Abril 2023
        '2023-01', // Janeiro 2023
    ];

    /**
     * Tenta encontrar uma versão válida da API do Shopify
     *
     * @param string $storeUrl
     * @param string $accessToken
     * @param string $preferredVersion
     * @return array ['version' => string|null, 'details' => array] Versão válida encontrada e detalhes das tentativas
     */
    public static function findValidVersion(
        string $storeUrl,
        string $accessToken,
        string $preferredVersion = '2024-01'
    ): array {
        // Se a versão preferida está na lista, testá-la primeiro
        $versionsToTry = array_unique([$preferredVersion, ...self::VALID_API_VERSIONS]);
        $details = [];

        foreach ($versionsToTry as $version) {
            $result = self::testApiVersion($storeUrl, $accessToken, $version);
            $details[$version] = $result;

            if ($result['valid']) {
                return [
                    'version' => $version,
                    'details' => $details,
                ];
            }
        }

        return [
            'version' => null,
            'details' => $details,
        ];
    }

    /**
     * Testa se uma versão específica da API funciona
     *
     * @param string $storeUrl
     * @param string $accessToken
     * @param string $version
     * @return array ['valid' => bool, 'status_code' => int, 'location' => string|null, 'error' => string|null]
     */
    private static function testApiVersion(
        string $storeUrl,
        string $accessToken,
        string $version
    ): array {
        try {
            $baseUrl = rtrim($storeUrl, '/') . '/admin/api/' . $version;
            $client = new Client([
                'base_uri' => $baseUrl,
                'headers' => [
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ],
                'timeout' => 5, // Timeout curto para teste rápido
                'allow_redirects' => true, // Seguir redirects normalmente
                'http_errors' => false,
            ]);

            $response = $client->get('/shop.json'); // Usar /shop.json que não precisa de permissões especiais

            $statusCode = $response->getStatusCode();
            $location = $response->getHeaderLine('Location');

            // Se retornou 200, a versão é válida e o token funciona
            if ($statusCode === 200) {
                return [
                    'valid' => true,
                    'status_code' => $statusCode,
                    'location' => null,
                    'error' => null,
                ];
            }

            // 401/403 significa que a API existe mas o token está errado
            // Isso ainda indica que a versão da API é válida
            if ($statusCode === 401 || $statusCode === 403) {
                return [
                    'valid' => true,
                    'status_code' => $statusCode,
                    'location' => null,
                    'error' => 'Token inválido ou sem permissão',
                ];
            }

            // 302 significa redirect (versão inválida ou token inválido)
            if ($statusCode >= 300 && $statusCode < 400) {
                // Se redireciona para /password, provavelmente é versão inválida ou token inválido
                return [
                    'valid' => false,
                    'status_code' => $statusCode,
                    'location' => $location,
                    'error' => str_contains($location, '/password') 
                        ? 'Redirect para página de senha (token ou versão inválida)' 
                        : "Redirect para: {$location}",
                ];
            }

            return [
                'valid' => false,
                'status_code' => $statusCode,
                'location' => null,
                'error' => "Status code não esperado: {$statusCode}",
            ];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'status_code' => null,
                'location' => null,
                'error' => $e->getMessage(),
            ];
        }
    }
}
