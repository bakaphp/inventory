<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants\Managers;

use Kanvas\Inventory\Contracts\ManagerInterface;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Managers\ProductWarehouseManager;
use Kanvas\Inventory\Variants\Models\ProductVariants;
use Kanvas\Inventory\Variants\Models\ProductVariantWarehouse as ModelsProductVariantWarehouse;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;

class ProductVariantWarehouseManager implements ManagerInterface
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

        $productWarehouse = new ProductWarehouseManager($this->productVariant->getProduct());
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
}
