<?php

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new ProductRepository();
});

test('it can create a product', function () {
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

    expect($product)->toBeInstanceOf(Product::class);
    expect($product->shopify_id)->toBe('123');
    expect($product->title)->toBe('Test Product');
    expect($product->price)->toBe(10.00);
});

test('it can find a product by shopify id', function () {
    $product = Product::factory()->create([
        'shopify_id' => '456',
        'title' => 'Existing Product',
    ]);

    $found = $this->repository->findByShopifyId('456');

    expect($found)->not->toBeNull();
    expect($found->id)->toBe($product->id);
    expect($found->shopify_id)->toBe('456');
});

test('it returns null when product not found by shopify id', function () {
    $found = $this->repository->findByShopifyId('999');

    expect($found)->toBeNull();
});

test('it can update a product', function () {
    $product = Product::factory()->create([
        'title' => 'Old Title',
        'price' => 10.00,
    ]);

    $updated = $this->repository->update($product, [
        'title' => 'New Title',
        'price' => 15.00,
    ]);

    expect($updated->title)->toBe('New Title');
    expect($updated->price)->toBe(15.00);
});

test('it can filter products by search', function () {
    Product::factory()->create(['title' => 'Apple iPhone']);
    Product::factory()->create(['title' => 'Samsung Galaxy']);
    Product::factory()->create(['title' => 'Google Pixel']);

    $results = $this->repository->getAll(['search' => 'Apple'], 10);

    expect($results->count())->toBe(1);
    expect($results->first()->title)->toContain('Apple');
});

test('it can filter products by vendor', function () {
    Product::factory()->create(['vendor' => 'Apple']);
    Product::factory()->create(['vendor' => 'Samsung']);
    Product::factory()->create(['vendor' => 'Apple']);

    $results = $this->repository->getAll(['vendor' => 'Apple'], 10);

    expect($results->count())->toBe(2);
});

test('it can filter products by product type', function () {
    Product::factory()->create(['product_type' => 'Electronics']);
    Product::factory()->create(['product_type' => 'Clothing']);
    Product::factory()->create(['product_type' => 'Electronics']);

    $results = $this->repository->getAll(['product_type' => 'Electronics'], 10);

    expect($results->count())->toBe(2);
});

test('it can paginate results', function () {
    Product::factory()->count(25)->create();

    $results = $this->repository->getAll([], 10);

    expect($results->count())->toBe(10);
    expect($results->total())->toBe(25);
});
