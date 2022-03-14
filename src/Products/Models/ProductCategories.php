<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Models;

use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Categories\Models\Categories;

class ProductCategories extends BaseModel
{
    public int $categories_id;
    public int $products_id;

    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('products_categories');

        $this->belongsTo(
            'categories_id',
            Categories::class,
            'id',
            [
                'alias' => 'category',
                'reusable' => true
            ]
        );

        $this->belongsTo(
            'products_id',
            Products::class,
            'id',
            [
                'alias' => 'product',
                'reusable' => true
            ]
        );
    }
}
