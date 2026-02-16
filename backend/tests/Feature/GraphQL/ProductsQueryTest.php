<?php

namespace Tests\Feature\GraphQL;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductsQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_query_products_via_graphql(): void
    {
        Product::factory()->count(5)->create();

        $query = '
            query {
                products(first: 10) {
                    data {
                        id
                        title
                        price
                        vendor
                    }
                    paginatorInfo {
                        total
                        count
                        currentPage
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $query,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'products' => [
                    'data' => [
                        '*' => ['id', 'title', 'price', 'vendor'],
                    ],
                    'paginatorInfo' => ['total', 'count', 'currentPage'],
                ],
            ],
        ]);

        $this->assertEquals(5, $response->json('data.products.paginatorInfo.total'));
    }

    public function test_it_can_filter_products_by_search_via_graphql(): void
    {
        Product::factory()->create(['title' => 'Apple iPhone']);
        Product::factory()->create(['title' => 'Samsung Galaxy']);
        Product::factory()->create(['title' => 'Google Pixel']);

        $query = '
            query {
                products(first: 10, search: "Apple") {
                    data {
                        id
                        title
                    }
                    paginatorInfo {
                        total
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $query,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(1, $response->json('data.products.paginatorInfo.total'));
        $this->assertStringContainsString('Apple', $response->json('data.products.data.0.title'));
    }

    public function test_it_can_filter_products_by_vendor_via_graphql(): void
    {
        Product::factory()->create(['vendor' => 'Apple']);
        Product::factory()->create(['vendor' => 'Samsung']);
        Product::factory()->create(['vendor' => 'Apple']);

        $query = '
            query {
                products(first: 10, vendor: "Apple") {
                    data {
                        id
                        vendor
                    }
                    paginatorInfo {
                        total
                    }
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $query,
        ]);

        $response->assertStatus(200);
        $this->assertEquals(2, $response->json('data.products.paginatorInfo.total'));
    }

    public function test_it_can_query_a_single_product_via_graphql(): void
    {
        $product = Product::factory()->create([
            'title' => 'Test Product',
            'price' => 99.99,
        ]);

        $query = '
            query {
                product(id: "' . $product->id . '") {
                    id
                    title
                    price
                }
            }
        ';

        $response = $this->postJson('/graphql', [
            'query' => $query,
        ]);

        $response->assertStatus(200);
        $this->assertEquals((string) $product->id, $response->json('data.product.id'));
        $this->assertEquals('Test Product', $response->json('data.product.title'));
        $this->assertEquals(99.99, $response->json('data.product.price'));
    }
}
