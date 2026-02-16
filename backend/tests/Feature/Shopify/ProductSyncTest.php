<?php

namespace Tests\Feature\Shopify;

use App\Contracts\Product\ProductRepositoryInterface;
use App\Contracts\Product\ProductSyncStrategyInterface;
use App\Contracts\Shopify\ShopifyProductApiInterface;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\Product\ProductSyncService;
use App\Services\Product\ProductTransformer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class ProductSyncTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Log::shouldReceive('error')->zeroOrMoreTimes();
    }

    public function test_it_can_sync_products_from_shopify(): void
    {
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
            ->with(Mockery::on(function ($arg) {
                return is_array($arg) && isset($arg['id']);
            }))
            ->twice()
            ->andReturn(true);
        $strategy->shouldReceive('transformProductData')
            ->twice()
            ->andReturnUsing(function ($product) {
                // Retornar apenas os campos essenciais para o teste
                return [
                    'shopify_id' => (string) $product['id'],
                    'title' => $product['title'] ?? '',
                    'description' => $product['body_html'] ?? null,
                    'price' => (float) ($product['variants'][0]['price'] ?? 0),
                    'vendor' => $product['vendor'] ?? null,
                    'product_type' => $product['product_type'] ?? null,
                    'status' => $product['status'] ?? 'active',
                    'synced_at' => now(),
                ];
            });

        $repository = new ProductRepository();

        $syncService = new ProductSyncService(
            $shopifyApi,
            $repository,
            $strategy
        );

        $stats = $syncService->syncProducts(250);

        $this->assertEquals(2, $stats['created']);
        $this->assertEquals(2, Product::count());
        $this->assertTrue(Product::where('shopify_id', '123')->exists());
        $this->assertTrue(Product::where('shopify_id', '456')->exists());
    }

    public function test_it_updates_existing_products_during_sync(): void
    {
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
            ->with(Mockery::on(function ($product) use ($existingProduct) {
                return $product instanceof Product && $product->id === $existingProduct->id;
            }), Mockery::on(function ($shopifyProduct) use ($shopifyProducts) {
                return is_array($shopifyProduct) && isset($shopifyProduct['id']) && $shopifyProduct['id'] === $shopifyProducts[0]['id'];
            }))
            ->andReturn(true);
        $strategy->shouldReceive('transformProductData')
            ->once()
            ->andReturnUsing(function ($product) {
                // Retornar apenas os campos essenciais para o teste
                return [
                    'shopify_id' => (string) $product['id'],
                    'title' => $product['title'] ?? '',
                    'description' => $product['body_html'] ?? null,
                    'price' => (float) ($product['variants'][0]['price'] ?? 0),
                    'vendor' => $product['vendor'] ?? null,
                    'product_type' => $product['product_type'] ?? null,
                    'status' => $product['status'] ?? 'active',
                    'synced_at' => now(),
                ];
            });

        $repository = new ProductRepository();

        $syncService = new ProductSyncService(
            $shopifyApi,
            $repository,
            $strategy
        );

        $stats = $syncService->syncProducts(250);

        $this->assertEquals(1, $stats['updated']);
        $existingProduct->refresh();
        $this->assertEquals('New Title', $existingProduct->title);
        $this->assertEquals(15.00, $existingProduct->price);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
