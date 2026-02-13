<?php

namespace App\GraphQL\Types;

use App\Models\Product;
use GraphQL\Type\Definition\Type;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class ProductType
{
    public function __invoke(): array
    {
        return [
            'id' => [
                'type' => Type::id(),
                'description' => 'The id of the product',
            ],
            'shopify_id' => [
                'type' => Type::string(),
                'description' => 'The Shopify ID of the product',
            ],
            'title' => [
                'type' => Type::string(),
                'description' => 'The title of the product',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'The description of the product',
            ],
            'price' => [
                'type' => Type::float(),
                'description' => 'The price of the product',
            ],
            'vendor' => [
                'type' => Type::string(),
                'description' => 'The vendor of the product',
            ],
            'product_type' => [
                'type' => Type::string(),
                'description' => 'The type of the product',
            ],
            'status' => [
                'type' => Type::string(),
                'description' => 'The status of the product',
            ],
            'synced_at' => [
                'type' => Type::string(),
                'description' => 'When the product was last synced',
            ],
            'created_at' => [
                'type' => Type::string(),
                'description' => 'When the product was created',
            ],
            'updated_at' => [
                'type' => Type::string(),
                'description' => 'When the product was last updated',
            ],
        ];
    }
}
