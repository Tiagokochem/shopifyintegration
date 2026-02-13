<?php

namespace App\Contracts\Shopify;

interface ShopifyApiInterface
{
    /**
     * Make a GET request to the Shopify API
     *
     * @param string $endpoint
     * @param array $params
     * @return array
     */
    public function get(string $endpoint, array $params = []): array;

    /**
     * Make a POST request to the Shopify API
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function post(string $endpoint, array $data = []): array;

    /**
     * Make a PUT request to the Shopify API
     *
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    public function put(string $endpoint, array $data = []): array;

    /**
     * Make a DELETE request to the Shopify API
     *
     * @param string $endpoint
     * @return array
     */
    public function delete(string $endpoint): array;
}
