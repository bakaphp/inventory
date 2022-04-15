<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Managers;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\Models\ProductsWarehouse;
use Kanvas\Inventory\Warehouses\Models\Warehouses;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;
use Phalcon\Mvc\Model\ResultsetInterface;

class ProductWarehouseManager
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

    /**
     * Get all the product by warehouse.
     *
     * @param UserInterface $user
     * @param Warehouses $warehouse
     * @param int $page
     * @param int $limit
     *
     * @return ResultsetInterface
     */
    public static function getAll(UserInterface $user, Warehouses $warehouse, int $page = 1, int $limit = 25) : ResultsetInterface
    {
        $offset = ($page - 1) * $limit;

        return Products::findByRawSql(
            'SELECT 
                p.*
            FROM 
                products p , 
                products_warehouse w
            WHERE
                p.id = w.products_id
                AND p.companies_id = ?
                AND w.warehouse_id = ?
                AND w.is_deleted = 0
                AND p.is_deleted = w.is_deleted
            LIMIT ?, ?',
            [
                $user->currentCompanyId(),
                $warehouse->getId(),
                $offset,
                $limit
            ]
        );
    }

    /**
     * Get one product from this warehouse.
     *
     * @param string $uuid
     * @param UserInterface $user
     * @param Warehouses $warehouse
     *
     * @return Products
     */
    public static function getByUuid(string $uuid, UserInterface $user, Warehouses $warehouse) : Products
    {
        return Products::findByRawSql(
            'SELECT 
                p.*
            FROM 
                products p , 
                products_warehouse w
            WHERE
                p.id = w.products_id
                AND w.warehouse_id = ?
                AND p.uuid = ?
                AND w.is_deleted = 0
                AND p.is_deleted = w.is_deleted
            LIMIT 1',
            [
                $warehouse->getId(),
                $uuid
            ]
        )->getFirst();
    }
}
