<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Models\Variants\Warehouse;

use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Products\Models\Variants;
use Kanvas\Inventory\Warehouses\Models\Warehouse as ModelsWarehouse;

class PriceHistory extends BaseModel
{
    public int $products_variants_id;
    public int $warehouse_id;
    public float $price = 0;
    public string $from_date;

    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('products_variants_warehouse_price_history');

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
    }
}
