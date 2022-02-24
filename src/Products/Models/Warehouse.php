<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Models;

use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;

class Warehouse extends BaseModel
{
    public int $products_id;
    public int $warehouse_id;
    public int $rating = 0;
    public int $is_published = 0;

    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('products_warehouse');

        $this->belongsTo(
            'products_id',
            Products::class,
            'id',
            [
                'alias' => 'product',
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
