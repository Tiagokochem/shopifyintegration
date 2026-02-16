<?php

namespace App\Services\Product;

use App\Exceptions\InvalidProductDataException;

/**
 * Product Validator
 * 
 * Validates product data before creation or update.
 * 
 * Demonstrates: Single Responsibility Principle, Separation of Concerns
 */
class ProductValidator
{
    /**
     * Validate product data for creation
     *
     * @param array $data
     * @throws InvalidProductDataException
     */
    public function validateCreate(array $data): void
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        }

        if (!isset($data['price']) || !is_numeric($data['price'])) {
            $errors['price'] = 'Price is required and must be a number';
        } elseif ($data['price'] < 0) {
            $errors['price'] = 'Price must be greater than or equal to 0';
        }

        if (isset($data['status']) && !in_array($data['status'], ['active', 'draft', 'archived'])) {
            $errors['status'] = 'Status must be one of: active, draft, archived';
        }

        if (isset($data['compare_at_price']) && is_numeric($data['compare_at_price']) && $data['compare_at_price'] < 0) {
            $errors['compare_at_price'] = 'Compare at price must be greater than or equal to 0';
        }

        if (isset($data['weight']) && is_numeric($data['weight']) && $data['weight'] < 0) {
            $errors['weight'] = 'Weight must be greater than or equal to 0';
        }

        if (isset($data['inventory_quantity']) && is_numeric($data['inventory_quantity']) && $data['inventory_quantity'] < 0) {
            $errors['inventory_quantity'] = 'Inventory quantity must be greater than or equal to 0';
        }

        if (!empty($errors)) {
            throw new InvalidProductDataException('Invalid product data', $errors);
        }
    }

    /**
     * Validate product data for update
     *
     * @param array $data
     * @throws InvalidProductDataException
     */
    public function validateUpdate(array $data): void
    {
        $errors = [];

        if (isset($data['title']) && empty($data['title'])) {
            $errors['title'] = 'Title cannot be empty';
        }

        if (isset($data['price'])) {
            if (!is_numeric($data['price'])) {
                $errors['price'] = 'Price must be a number';
            } elseif ($data['price'] < 0) {
                $errors['price'] = 'Price must be greater than or equal to 0';
            }
        }

        if (isset($data['status']) && !in_array($data['status'], ['active', 'draft', 'archived'])) {
            $errors['status'] = 'Status must be one of: active, draft, archived';
        }

        if (isset($data['compare_at_price']) && is_numeric($data['compare_at_price']) && $data['compare_at_price'] < 0) {
            $errors['compare_at_price'] = 'Compare at price must be greater than or equal to 0';
        }

        if (isset($data['weight']) && is_numeric($data['weight']) && $data['weight'] < 0) {
            $errors['weight'] = 'Weight must be greater than or equal to 0';
        }

        if (isset($data['inventory_quantity']) && is_numeric($data['inventory_quantity']) && $data['inventory_quantity'] < 0) {
            $errors['inventory_quantity'] = 'Inventory quantity must be greater than or equal to 0';
        }

        if (!empty($errors)) {
            throw new InvalidProductDataException('Invalid product data', $errors);
        }
    }
}
