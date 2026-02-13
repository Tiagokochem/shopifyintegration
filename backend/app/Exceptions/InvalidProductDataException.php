<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Invalid Product Data Exception
 * 
 * Thrown when product data validation fails.
 * 
 * Demonstrates: Specific exception types for better error handling
 */
class InvalidProductDataException extends RuntimeException
{
    public function __construct(string $message, array $errors = [])
    {
        $fullMessage = $message;
        if (!empty($errors)) {
            $fullMessage .= ': ' . json_encode($errors);
        }
        parent::__construct($fullMessage, 422);
    }
}
