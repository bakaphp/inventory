<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants\Models;

use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Channels\Models\Channels;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Variants;
use Kanvas\Inventory\Variants\ProductVariantWarehouseChannelPrice;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;

class ProductVariantWarehouseChannels extends BaseModel
{
    public int $products_variants_id;
    public int $warehouse_id;
    public int $channels_id;
    public float $price = 0;
    public float $discounted_price = 0;
    public int $is_published = State::PUBLISHED;

    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('products_variants_warehouse_channels');

        $this->belongsTo(
            'products_variants_id',
            Variants::class,
            'id',
            [
                'alias' => 'variant',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'warehouse_id',
            ModelsWarehouse::class,
            'id',
            [
                'alias' => 'warehouse',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'channels_id',
            Channels::class,
            'id',
            [
                'alias' => 'channel',
                'reusable' => true
            ]
        );

        $this->hasMany(
            'products_variants_id',
            ProductVariantWarehousePriceHistory::class,
            'products_variants_id',
            [
                'alias' => 'priceHistory',
                'reusable' => true
            ]
        );
    }

    /**
     * After save event.
     *
     * @return void
     */
    public function afterSave()
    {
        $priceHistory = new ProductVariantWarehouseChannelPrice($this);
        $priceHistory->updatePrice($this->price);
    }
}
