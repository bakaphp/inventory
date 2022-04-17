<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Managers;

use Kanvas\Inventory\Contracts\ManagerInterface;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\Models\ProductsWarehouse;
use Kanvas\Inventory\Warehouses\Models\Warehouses;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;

class ProductWarehouseManager implements ManagerInterface
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
     * @return ProductsWarehouse
     */
    public function add(ModelsWarehouse $warehouse, int $isPublished = State::PUBLISHED, int $rating = 0) : ProductsWarehouse
    {
        return ProductsWarehouse::findFirstOrCreate([
            'conditions' => 'products_id = :products_id: AND warehouse_id = :warehouses_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
                'warehouses_id' => $warehouse->getId(),
            ]
        ], [
            'warehouse_id' => $warehouse->getId(),
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
     * @return list <ModelsWarehouse>
     */
    public function addMultiples(array $warehouses) : array
    {
        $results = [];
        foreach ($warehouses as $warehouse) {
            $results[] = $this->add($warehouse);
        }

        return $results;
    }

    /**
     * Soft Delete.
     *
     * @param ModelsWarehouse $warehouse
     *
     * @return bool
     */
    public function delete(ModelsWarehouse $warehouse) : bool
    {
        $productWarehouse = ProductsWarehouse::findFirst([
            'conditions' => 'products_id = :products_id: AND warehouse_id = :warehouses_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
                'warehouses_id' => $warehouse->getId(),
            ]
        ]);

        if ($productWarehouse) {
            return $productWarehouse->softDelete();
        }

        return false;
    }
}
