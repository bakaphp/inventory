<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants\Models;

use Kanvas\Inventory\Attributes\Models\Attributes as ModelsAttributes;
use Kanvas\Inventory\Products\Models\Attributes as ProductsModelsAttributes;
use Kanvas\Inventory\Products\Models\Variants;

class Attributes extends ProductsModelsAttributes
{
    public int $products_variants_id;
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

        $this->setSource('products_variants_attributes');

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
