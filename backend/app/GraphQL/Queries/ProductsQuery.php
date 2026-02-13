<?php

namespace App\GraphQL\Queries;

use App\Contracts\Product\ProductRepositoryInterface;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProductsQuery
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {
    }

    public function __invoke($rootValue, array $args, GraphQLContext $context)
    {
        $filters = [];

        if (isset($args['search'])) {
            $filters['search'] = $args['search'];
        }

        if (isset($args['vendor'])) {
            $filters['vendor'] = $args['vendor'];
        }

        if (isset($args['product_type'])) {
            $filters['product_type'] = $args['product_type'];
        }

        $perPage = $args['first'] ?? 10;
        $page = $args['page'] ?? 1;

        $paginator = $this->productRepository->getAll($filters, $perPage);
        
        // Se retornar paginator do Laravel, transformar para formato GraphQL
        if (method_exists($paginator, 'items')) {
            return [
                'data' => $paginator->items(),
                'paginatorInfo' => [
                    'count' => $paginator->count(),
                    'currentPage' => $paginator->currentPage(),
                    'firstItem' => $paginator->firstItem(),
                    'hasMorePages' => $paginator->hasMorePages(),
                    'lastItem' => $paginator->lastItem(),
                    'lastPage' => $paginator->lastPage(),
                    'perPage' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ],
            ];
        }

        // Se retornar collection, criar formato de paginação manual
        return [
            'data' => $paginator->toArray(),
            'paginatorInfo' => [
                'count' => $paginator->count(),
                'currentPage' => $page,
                'firstItem' => 1,
                'hasMorePages' => false,
                'lastItem' => $paginator->count(),
                'lastPage' => 1,
                'perPage' => $perPage,
                'total' => $paginator->count(),
            ],
        ];
    }
}
