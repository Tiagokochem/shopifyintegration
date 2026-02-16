<?php

use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Shopify\ShopifyProductApiInterface;
use App\Models\Product;
use App\Services\Product\ProductSyncService;
use App\Services\Product\ProductTransformer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can sync products from shopify', function () {
    $shopifyProducts = [
        [
            'id' => '123',
            'title' => 'Test Product 1',
            'body_html' => 'Description 1',
            'variants' => [['price' => '10.00']],
            'vendor' => 'Vendor 1',
            'product_type' => 'Type 1',
            'status' => 'active',
        ],
        [
            'id' => '456',
            'title' => 'Test Product 2',
            'body_html' => 'Description 2',
            'variants' => [['price' => '20.00']],
            'vendor' => 'Vendor 2',
            'product_type' => 'Type 2',
            'status' => 'active',
        ],
    ];

    $shopifyApi = Mockery::mock(ShopifyProductApiInterface::class);
    $shopifyApi->shouldReceive('getProducts')
        ->once()
        ->andReturn($shopifyProducts);

    $strategy = Mockery::mock(ProductSyncStrategyInterface::class);
    $strategy->shouldReceive('shouldCreate')
        ->twice()
        ->andReturn(true);
    $strategy->shouldReceive('transformProductData')
        ->twice()
        ->andReturnUsing(function ($product) {
            $transformer = new ProductTransformer();
            return $transformer->transform($product);
        });

    $repository = new \App\Repositories\ProductRepository();

    $syncService = new ProductSyncService(
        $shopifyApi,
        $repository,
        $strategy
    );

    $stats = $syncService->syncProducts(250);

    expect($stats['created'])->toBe(2);
    expect(Product::count())->toBe(2);
    expect(Product::where('shopify_id', '123')->exists())->toBeTrue();
    expect(Product::where('shopify_id', '456')->exists())->toBeTrue();
});

test('it updates existing products during sync', function () {
    $existingProduct = Product::factory()->create([
        'shopify_id' => '123',
        'title' => 'Old Title',
        'price' => 10.00,
    ]);

    $shopifyProducts = [
        [
            'id' => '123',
            'title' => 'New Title',
            'body_html' => 'New Description',
            'variants' => [['price' => '15.00']],
            'vendor' => 'Updated Vendor',
            'product_type' => 'Updated Type',
            'status' => 'active',
        ],
    ];

    $shopifyApi = Mockery::mock(ShopifyProductApiInterface::class);
    $shopifyApi->shouldReceive('getProducts')
        ->once()
        ->andReturn($shopifyProducts);

    $strategy = Mockery::mock(ProductSyncStrategyInterface::class);
    $strategy->shouldReceive('shouldUpdate')
        ->once()
        ->andReturn(true);
    $strategy->shouldReceive('transformProductData')
        ->once()
        ->andReturnUsing(function ($product) {
            $transformer = new ProductTransformer();
            return $transformer->transform($product);
        });

    $repository = new \App\Repositories\ProductRepository();

    $syncService = new ProductSyncService(
        $shopifyApi,
        $repository,
        $strategy
    );

    $stats = $syncService->syncProducts(250);

    expect($stats['updated'])->toBe(1);
    $existingProduct->refresh();
    expect($existingProduct->title)->toBe('New Title');
    expect($existingProduct->price)->toBe(15.00);
});
