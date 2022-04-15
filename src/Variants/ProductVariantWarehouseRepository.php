<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Variants\Models\ProductVariantWarehouse as ModelsProductVariantWarehouse;
use Kanvas\Inventory\Warehouses\Models\Warehouses;
use Phalcon\Mvc\Model\ResultsetInterface;

class ProductVariantWarehouseRepository
{
    /**
     * Get all the product variants from this warehouse.
     *
     * @param UserInterface $user
     * @param Warehouses $warehouse
     * @param int $page
     * @param int $limit
     *
     * @return ResultsetInterface<ModelsProductVariantWarehouse>
     */
    public static function getAll(UserInterface $user, Warehouses $warehouse, int $page = 1, int $limit = 25) : ResultsetInterface
    {
        $offset = ($page - 1) * $limit;

        return ModelsProductVariantWarehouse::findByRawSql(
            'SELECT 
            w.*
            FROM 
                products p,
                products_variants v, 
                products_variants_warehouse w
            WHERE
                v.products_id = p.id
                AND p.companies_id = ?
                AND w.products_variants_id = v.id
                AND w.warehouse_id = ?
                AND w.is_deleted = p.is_deleted
                AND w.is_deleted = 0
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
     * Get variant by uuid.
     *
     * @param string $uuid
     * @param UserInterface $user
     * @param Warehouses $warehouse
     *
     * @return ModelsProductVariantWarehouse
     */
    public static function getByUuid(string $uuid, UserInterface $user, Warehouses $warehouse) : ModelsProductVariantWarehouse
    {
        return ModelsProductVariantWarehouse::findByRawSql(
            'SELECT 
            w.*
            FROM 
                products p,
                products_variants v, 
                products_variants_warehouse w
            WHERE
                v.products_id = p.id
                AND p.companies_id = ?
                AND w.products_variants_id = v.id
                AND v.uuid = ?
                AND w.warehouse_id = ?
                AND w.is_deleted = p.is_deleted
                AND w.is_deleted = v.is_deleted
                AND w.is_deleted = 0
            LIMIT 1',
            [
                $user->currentCompanyId(),
                $uuid,
                $warehouse->getId(),
            ]
        )->getFirst();
    }

    /**
     * Get all the product variants from this warehouse.
     *
     * @param UserInterface $user
     * @param Warehouses $warehouse
     * @param int $page
     * @param int $limit
     *
     * @return ResultsetInterface
     */
    public static function getAllByProduct(UserInterface $user, Warehouses $warehouse, Products $product, int $page = 1, int $limit = 10) : ResultsetInterface
    {
        $offset = ($page - 1) * $limit;

        return ModelsProductVariantWarehouse::findByRawSql(
            'SELECT 
            v.*
            FROM 
                products p,
                products_variants v, 
                products_variants_warehouse w
            WHERE
                v.products_id = p.id
                AND p.id = ?
                AND p.companies_id = ?
                AND w.products_variants_id = v.id
                AND w.warehouse_id = ?
                AND w.is_deleted = p.is_deleted
                AND w.is_deleted = v.is_deleted
                AND w.is_deleted = 0
            LIMIT ?, ?',
            [
                $product->getId(),
                $user->currentCompanyId(),
                $warehouse->getId(),
                $offset,
                $limit
            ]
        );
    }
}
