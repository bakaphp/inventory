<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants;

use Kanvas\Inventory\Variants\Models\ProductVariantWarehouse;
use Kanvas\Inventory\Variants\Models\ProductVariantWarehouseChannels;
use Kanvas\Inventory\Variants\Models\ProductVariantWarehousePriceHistory;

class ProductVariantWarehouseChannelPrice
{
    protected ProductVariantWarehouseChannels $productVariantWarehouseChannel;

    /**
     * Construct.
     *
     * @param ProductVariantWarehouse $variant
     */
    public function __construct(ProductVariantWarehouseChannels $productVariantWarehouseChannel)
    {
        $this->productVariantWarehouseChannel = $productVariantWarehouseChannel;
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
                            AND channels_id = :channels_id:
                            AND price = :price:',
            'bind' => [
                'products_variants_id' => $this->productVariantWarehouseChannel->products_variants_id,
                'warehouse_id' => $this->productVariantWarehouseChannel->warehouse_id,
                'channels_id' => $this->productVariantWarehouseChannel->channels_id,
                'price' => $price,
            ],
            'order' => 'from_date desc'
        ], [
            'products_variants_id' => $this->productVariantWarehouseChannel->products_variants_id,
            'warehouse_id' => $this->productVariantWarehouseChannel->warehouse_id,
            'price' => $price,
            'channels_id' => $this->productVariantWarehouseChannel->channels_id,
            'from_date' => date('Y-m-d H:i:s')
        ]);
    }
}
