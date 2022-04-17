<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Actions;

use Kanvas\Inventory\Products\Models\Products;

class CreateMultipleProductAction
{
    /**
     * Create multiple products.
     *
     * @param array $products<string, string>
     *
     * @return array<int,Products>
     */
    public static function execute(array $products) : array
    {
        $results = [];
        foreach ($products as $product) {
            if (!isset($product['user']) || !isset($product['name']) || !isset($product['category'])) {
                continue;
            }

            $results[] = CreateProductAction::execute(
                $product['user'],
                $product['name'],
                $product['category'],
                $product['options']
            );
        }

        return $results;
    }
}
