<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Product Not Found Exception
 * 
 * Thrown when a product is not found in the database.
 * 
 * Demonstrates: Specific exception types for better error handling
 */
class ProductNotFoundException extends RuntimeException
{
    public function __construct(string|int $productId)
    {
        parent::__construct("Product with ID {$productId} not found", 404);
    }
}
