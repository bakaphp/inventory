<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants;

use Kanvas\Inventory\Variants\Models\ProductVariantWarehouse;
use Kanvas\Inventory\Variants\Models\ProductVariantWarehousePriceHistory;

class ProductVariantWarehousePrice
{
    protected ProductVariantWarehouse $productVariantWarehouse;

    /**
     * Construct.
     *
     * @param ProductVariantWarehouse $variant
     */
    public function __construct(ProductVariantWarehouse $productVariantWarehouse)
    {
        $this->productVariantWarehouse = $productVariantWarehouse;
    }

    /**
     * Update the price.
     *
     * @param float $price
     *
     * @return ProductVariantWarehousePriceHistory
     */
    public function updatePrice(float $price) : ProductVariantWarehousePriceHistory
    {
        return ProductVariantWarehousePriceHistory::findFirstOrCreate([
            'conditions' => 'products_variants_id = :products_variants_id: 
                            AND warehouse_id = :warehouse_id:
                            AND price = :price:',
            'bind' => [
                'products_variants_id' => $this->productVariantWarehouse->products_variants_id,
                'warehouse_id' => $this->productVariantWarehouse->warehouse_id,
                'price' => $price,
            ],
            'order' => 'from_date desc'
        ], [
            'products_variants_id' => $this->productVariantWarehouse->products_variants_id,
            'warehouse_id' => $this->productVariantWarehouse->warehouse_id,
            'price' => $price,
            'from_date' => date('Y-m-d H:i:s')
        ]);
    }
}
