<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Models;

use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\Attributes\Models\Attributes as ModelsAttributes;
use Kanvas\Inventory\BaseModel;

class Attributes extends BaseModel
{
    public int $products_id;
    public int $attributes_id;
    public ?string $value = null;

    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->addBehavior(
            new Uuid()
        );

        $this->setSource('products_attributes');

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
            'attributes_id',
            ModelsAttributes::class,
            'id',
            [
                'alias' => 'attribute',
                'reusable' => true
            ]
        );
    }
}
