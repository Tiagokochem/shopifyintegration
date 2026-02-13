<?php

namespace App\Repositories;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function findByShopifyId(string $shopifyId): ?Product
    {
        return Product::where('shopify_id', $shopifyId)->first();
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product->fresh();
    }

    public function getAll(array $filters = [], int $perPage = 10, int $page = 1)
    {
        $query = Product::query();

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['vendor'])) {
            $query->where('vendor', $filters['vendor']);
        }

        if (isset($filters['product_type'])) {
            $query->where('product_type', $filters['product_type']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if ($perPage > 0) {
            return $query->orderBy('created_at', 'desc')->paginate($perPage, ['*'], 'page', $page);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    /**
     * Find a product by ID (accepts string or int for GraphQL compatibility)
     *
     * @param string|int $id
     * @return Product|null
     */
    public function findByIdOrFail(string|int $id): ?Product
    {
        $id = is_string($id) ? (int) $id : $id;
        return $this->findById($id);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }
}
