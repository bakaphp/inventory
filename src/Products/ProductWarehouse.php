<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products;

use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Variants\Models\Warehouse;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;

class ProductWarehouse
{
    protected Products $product;

    /**
     * Constructor.
     *
     * @param Products $product
     */
    public function __construct(Products $product)
    {
        $this->product = $product;
    }

    /**
     * Add warehouse for this product.
     *
     * @param ModelsWarehouse $warehouse
     * @param int $isPublished
     * @param int $rating
     *
     * @return ModelsWarehouse
     */
    public function add(ModelsWarehouse $warehouse, int $isPublished = State::PUBLISHED, int $rating = 0) : ModelsWarehouse
    {
        return Warehouse::findFirstOrCreate([
            'conditions' => 'products_id = :products_id: AND warehouses_id = :warehouses_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
                'warehouses_id' => $warehouse->getId(),
            ]
        ], [
            'warehouses_id' => $warehouse->getId(),
            'products_id' => $this->product->getId(),
            'is_published' => $isPublished,
            'rating' => $rating,
        ]);
    }

    /**
     * Add multiped warehouses.
     *
     * @param array $warehouses<int, ModelsWarehouse>
     *
     * @return array<int, ModelsWarehouse>
     */
    public function addMultiples(array $warehouses) : array
    {
        $results = [];
        foreach ($warehouses as $warehouse) {
            $results[] = $this->add($warehouse);
        }

        return $results;
    }
}
