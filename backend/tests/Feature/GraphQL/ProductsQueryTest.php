<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it can query products via graphql', function () {
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

    expect($response->json('data.products.paginatorInfo.total'))->toBe(5);
});

test('it can filter products by search via graphql', function () {
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
    expect($response->json('data.products.paginatorInfo.total'))->toBe(1);
    expect($response->json('data.products.data.0.title'))->toContain('Apple');
});

test('it can filter products by vendor via graphql', function () {
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
    expect($response->json('data.products.paginatorInfo.total'))->toBe(2);
});

test('it can query a single product via graphql', function () {
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
    expect($response->json('data.product.id'))->toBe((string) $product->id);
    expect($response->json('data.product.title'))->toBe('Test Product');
    expect($response->json('data.product.price'))->toBe(99.99);
});
