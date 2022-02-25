<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants\Models;

use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Products\Models\Variants;
use Kanvas\Inventory\Products\Models\Variants\Warehouse\PriceHistory;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;

class Warehouse extends BaseModel
{
    public int $products_variants_id;
    public int $warehouse_id;
    public int $quantity = 0;
    public float $price = 0;
    public string $sku;
    public ?string $serial_number = null;
    public int $is_oversellable = 0;
    public int $is_out_of_stock_on_store = 0;
    public int $is_default = 0;
    public int $is_best_seller = 0;
    public int $is_on_sale = 0;
    public int $is_on_promo = 0;
    public int $can_pre_order = 0;
    public int $is_coming_soon = 0;
    public int $is_new = 0;
    public int $is_published = 0;
    public int $position = 0;

    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('products_variants_warehouse');

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

        $this->hasMany(
            'products_variants_id',
            PriceHistory::class,
            'products_variants_id',
            [
                'alias' => 'priceHistory',
                'reusable' => true
            ]
        );
    }
}
