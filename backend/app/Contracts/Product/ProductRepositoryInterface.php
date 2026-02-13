<?php

namespace App\Contracts\Product;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * Find a product by Shopify ID
     *
     * @param string $shopifyId
     * @return Product|null
     */
    public function findByShopifyId(string $shopifyId): ?Product;

    /**
     * Create a new product
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product;

    /**
     * Update an existing product
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function update(Product $product, array $data): Product;

    /**
     * Get all products with optional filters
     *
     * @param array $filters
     * @param int $perPage
     * @param int $page
     * @return Collection|\Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAll(array $filters = [], int $perPage = 10, int $page = 1);

    /**
     * Find a product by ID
     *
     * @param int $id
     * @return Product|null
     */
    public function findById(int $id): ?Product;
}
