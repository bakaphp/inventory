<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\ProductWarehouse;
use Kanvas\Inventory\Variants\Models\ProductVariants;
use Kanvas\Inventory\Variants\Models\ProductVariantWarehouse as ModelsProductVariantWarehouse;
use Kanvas\Inventory\Warehouses\Models\Warehouses;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;
use Phalcon\Mvc\Model\ResultsetInterface;

class ProductVariantWarehouse
{
    protected ProductVariants $productVariant;

    /**
     * Construct.
     *
     * @param ProductVariants $variant
     */
    public function __construct(ProductVariants $productVariant)
    {
        $this->productVariant = $productVariant;
    }

    /**
     * Add new variant warehouse.
     *
     * @param ModelsWarehouse $warehouse
     * @param int $quantity
     * @param float $price
     * @param string $sku
     * @param array $options
     *
     * @return ModelsProductVariantWarehouse
     */
    public function add(
        ModelsWarehouse $warehouse,
        int $quantity,
        float $price,
        string $sku,
        array $options = []
    ) : ModelsProductVariantWarehouse {
        $isPublished = isset($options['is_published']) ? (int) $options['is_published'] : State::PUBLISHED;

        $productWarehouse = new ProductWarehouse($this->productVariant->getProduct());
        $productWarehouse->add($warehouse, $isPublished, 0);

        $productVariantWarehouse = new ModelsProductVariantWarehouse();
        $productVariantWarehouse->products_variants_id = $this->productVariant->getId();
        $productVariantWarehouse->warehouse_id = $warehouse->getId();
        $productVariantWarehouse->quantity = $quantity;
        $productVariantWarehouse->price = $price;
        $productVariantWarehouse->sku = $sku;
        $productVariantWarehouse->serial_number = $options['serial_number'] ?? null;
        $productVariantWarehouse->is_oversellable = $options['is_oversellable'] ?? 0;
        $productVariantWarehouse->is_out_of_stock_on_store = $options['is_out_of_stock_on_store'] ?? 0;
        $productVariantWarehouse->is_default = $options['is_default'] ?? 0;
        $productVariantWarehouse->is_best_seller = $options['is_best_seller'] ?? 0;
        $productVariantWarehouse->is_on_sale = $options['is_on_sale'] ?? 0;
        $productVariantWarehouse->is_on_promo = $options['is_on_promo'] ?? 0;
        $productVariantWarehouse->is_coming_soon = $options['is_coming_soon'] ?? 0;
        $productVariantWarehouse->is_new = $options['is_new'] ?? 0;
        $productVariantWarehouse->position = isset($options['position']) && (int) $options['position'] > 0 ? $options['position'] : State::DEFAULT_POSITION;
        $productVariantWarehouse->is_published = $isPublished;
        $productVariantWarehouse->saveOrFail();

        //missing price history

        return $productVariantWarehouse;
    }

    /**
     * Move a product variant to another warehouse.
     *
     * @param ModelsWarehouse $warehouse
     * @param ModelsWarehouse $newWarehouse
     *
     * @return bool
     */
    public function move(ModelsWarehouse $warehouse, ModelsWarehouse $newWarehouse) : bool
    {
        $productVariantWarehouse = ModelsProductVariantWarehouse::findFirst([
            'conditions' => 'products_variants_id = ?0 AND warehouses_id = ?1',
            'bind' => [
                $this->productVariant->getId(),
                $warehouse->getId(),
            ],
        ]);

        if (!$productVariantWarehouse) {
            return false;
        }

        $productVariantWarehouse->warehouses_id = $newWarehouse->getId();
        $productVariantWarehouse->saveOrFail();

        return true;
    }

    /**
     * Remove a product variant from a warehouse.
     *
     * @param ModelsWarehouse $warehouse
     *
     * @return bool
     */
    public function delete(ModelsWarehouse $warehouse) : bool
    {
        $productVariantWarehouse = ModelsProductVariantWarehouse::findFirst([
            'conditions' => 'products_variants_id = ?0 AND warehouses_id = ?1 AND is_deleted = 0',
            'bind' => [
                $this->productVariant->getId(),
                $warehouse->getId(),
            ],
        ]);

        if (!$productVariantWarehouse) {
            return false;
        }

        $productVariantWarehouse->softDelete();

        return true;
    }

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
