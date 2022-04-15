<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Warehouses\Models\Warehouses;
use Phalcon\Mvc\Model\ResultsetInterface;

class ProductWarehouseRepository
{
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
