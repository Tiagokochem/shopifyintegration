<?php

use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Shopify\ShopifyProductApiInterface;
use App\Models\Product;
use App\Services\Product\ProductSyncService;
use App\Services\Product\ProductTransformer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->shopifyApi = Mockery::mock(ShopifyProductApiInterface::class);
    $this->repository = Mockery::mock(ProductRepositoryInterface::class);
    $this->strategy = Mockery::mock(ProductSyncStrategyInterface::class);

    $this->syncService = new ProductSyncService(
        $this->shopifyApi,
        $this->repository,
        $this->strategy
    );
});

test('it syncs products successfully', function () {
    Event::fake();
    Log::shouldReceive('error')->zeroOrMoreTimes();

    $shopifyProducts = [
        [
            'id' => '123',
            'title' => 'Test Product',
            'body_html' => 'Description',
            'variants' => [['price' => '10.00']],
            'vendor' => 'Test Vendor',
            'product_type' => 'Test Type',
            'status' => 'active',
        ],
    ];

    $this->shopifyApi
        ->shouldReceive('getProducts')
        ->once()
        ->with(250)
        ->andReturn($shopifyProducts);

    $this->repository
        ->shouldReceive('findByShopifyId')
        ->once()
        ->with('123')
        ->andReturn(null);

    $this->strategy
        ->shouldReceive('shouldCreate')
        ->once()
        ->with($shopifyProducts[0])
        ->andReturn(true);

    $this->strategy
        ->shouldReceive('transformProductData')
        ->once()
        ->with($shopifyProducts[0])
        ->andReturn([
            'shopify_id' => '123',
            'title' => 'Test Product',
            'description' => 'Description',
            'price' => 10.00,
            'vendor' => 'Test Vendor',
            'product_type' => 'Test Type',
            'status' => 'active',
            'synced_at' => now(),
        ]);

    $product = new Product();
    $product->id = 1;

    $this->repository
        ->shouldReceive('create')
        ->once()
        ->andReturn($product);

    $stats = $this->syncService->syncProducts(250);

    expect($stats['created'])->toBe(1);
    expect($stats['updated'])->toBe(0);
    expect($stats['skipped'])->toBe(0);
    expect($stats['errors'])->toBe(0);
});

test('it updates existing products', function () {
    Event::fake();
    Log::shouldReceive('error')->zeroOrMoreTimes();

    $shopifyProducts = [
        [
            'id' => '123',
            'title' => 'Updated Product',
            'body_html' => 'Updated Description',
            'variants' => [['price' => '15.00']],
            'vendor' => 'Test Vendor',
            'product_type' => 'Test Type',
            'status' => 'active',
        ],
    ];

    $existingProduct = new Product();
    $existingProduct->id = 1;
    $existingProduct->shopify_id = '123';
    $existingProduct->title = 'Old Title';

    $this->shopifyApi
        ->shouldReceive('getProducts')
        ->once()
        ->andReturn($shopifyProducts);

    $this->repository
        ->shouldReceive('findByShopifyId')
        ->once()
        ->with('123')
        ->andReturn($existingProduct);

    $this->strategy
        ->shouldReceive('shouldUpdate')
        ->once()
        ->with($existingProduct, $shopifyProducts[0])
        ->andReturn(true);

    $this->strategy
        ->shouldReceive('transformProductData')
        ->once()
        ->with($shopifyProducts[0])
        ->andReturn([
            'shopify_id' => '123',
            'title' => 'Updated Product',
            'description' => 'Updated Description',
            'price' => 15.00,
            'vendor' => 'Test Vendor',
            'product_type' => 'Test Type',
            'status' => 'active',
            'synced_at' => now(),
        ]);

    $this->repository
        ->shouldReceive('update')
        ->once()
        ->with($existingProduct, Mockery::type('array'))
        ->andReturn($existingProduct);

    $stats = $this->syncService->syncProducts(250);

    expect($stats['created'])->toBe(0);
    expect($stats['updated'])->toBe(1);
});

test('it handles errors gracefully', function () {
    Event::fake();
    Log::shouldReceive('error')->zeroOrMoreTimes();

    $this->shopifyApi
        ->shouldReceive('getProducts')
        ->once()
        ->andThrow(new \Exception('API Error'));

    expect(fn () => $this->syncService->syncProducts(250))
        ->toThrow(\Exception::class);
});
