<?php

namespace Tests\Unit\Repositories;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ProductRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new ProductRepository();
    }

    public function test_it_can_create_a_product(): void
    {
        $data = [
            'shopify_id' => '123',
            'title' => 'Test Product',
            'description' => 'Test Description',
            'price' => 10.00,
            'vendor' => 'Test Vendor',
            'product_type' => 'Test Type',
            'status' => 'active',
            'synced_at' => now(),
        ];

        $product = $this->repository->create($data);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('123', $product->shopify_id);
        $this->assertEquals('Test Product', $product->title);
        $this->assertEquals(10.00, $product->price);
    }

    public function test_it_can_find_a_product_by_shopify_id(): void
    {
        $product = Product::factory()->create([
            'shopify_id' => '456',
            'title' => 'Existing Product',
        ]);

        $found = $this->repository->findByShopifyId('456');

        $this->assertNotNull($found);
        $this->assertEquals($product->id, $found->id);
        $this->assertEquals('456', $found->shopify_id);
    }

    public function test_it_returns_null_when_product_not_found_by_shopify_id(): void
    {
        $found = $this->repository->findByShopifyId('999');

        $this->assertNull($found);
    }

    public function test_it_can_update_a_product(): void
    {
        $product = Product::factory()->create([
            'title' => 'Old Title',
            'price' => 10.00,
        ]);

        $updated = $this->repository->update($product, [
            'title' => 'New Title',
            'price' => 15.00,
        ]);

        $this->assertEquals('New Title', $updated->title);
        $this->assertEquals(15.00, $updated->price);
    }

    public function test_it_can_filter_products_by_search(): void
    {
        Product::factory()->create(['title' => 'Laptop Computer']);
        Product::factory()->create(['title' => 'Desktop Computer']);
        Product::factory()->create(['title' => 'Mouse']);

        $results = $this->repository->getAll(['search' => 'Computer'], 10);

        $this->assertCount(2, $results);
        $this->assertTrue($results->every(fn ($product) => str_contains($product->title, 'Computer')));
    }

    public function test_it_can_paginate_products(): void
    {
        Product::factory()->count(25)->create();

        $paginated = $this->repository->getAll([], 10);

        $this->assertCount(10, $paginated);
        $this->assertEquals(25, $paginated->total());
        $this->assertEquals(3, $paginated->lastPage());
    }

    public function test_it_can_get_all_products(): void
    {
        Product::factory()->count(5)->create();

        $products = $this->repository->getAll([], 10);

        $this->assertGreaterThanOrEqual(5, $products->count());
    }

    public function test_it_can_delete_a_product(): void
    {
        $product = Product::factory()->create();

        $this->repository->delete($product);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
