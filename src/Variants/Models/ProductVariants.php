<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants\Models;

use Baka\Support\Str;
use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\Attributes\Models\Attributes as ModelsAttributes;
use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Variants\ProductVariantAttribute;
use Kanvas\Inventory\Variants\ProductVariantWarehouse;
use Phalcon\Mvc\Model\Resultset\Simple ;

class ProductVariants extends BaseModel
{
    public int $products_id;
    public string $uuid;
    public string $name;
    public string $slug;
    public ?string $description = null;
    public ?string $short_description = null;
    public string $sku;
    public ?string $ean = null;
    public ?string $barcode = null;
    public ?string $serial_number = null;
    public int $position = 0;
    public int $is_published = 0;

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

        $this->setSource('products_variants');

        $this->belongsTo(
            'products_id',
            Products::class,
            'id',
            [
                'alias' => 'product',
                'reusable' => true
            ]
        );

        $this->hasManyToMany(
            'id',
            ProductVariantAttributes::class,
            'products_variants_id',
            'attributes_id',
            ModelsAttributes::class,
            'id',
            [
                'alias' => 'attributes',
                'elasticIndex' => false,
                'reusable' => true,
            ]
        );

        //return only the attributes => value
    }

    /**
     * Before create.
     *
     * @return void
     */
    public function beforeCreate()
    {
        parent::beforeCreate();

        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
            $this->short_slug = $this->slug;
        }
    }

    /**
     * Get product.
     *
     * @return Products
     */
    public function getProduct() : Products
    {
        return $this->product;
    }

    /**
     * Get attributes.
     *
     * @return Simple
     */
    public function getAttributes() : Simple
    {
        return $this->attributes;
    }

    /**
     * Variant Warehouse domain.
     *
     * @return ProductVariantWarehouse
     */
    public function warehouse() : ProductVariantWarehouse
    {
        return new ProductVariantWarehouse($this);
    }

    /**
     * Variant Attribute domain.
     *
     * @return ProductVariantAttribute
     */
    public function attribute() : ProductVariantAttribute
    {
        return new ProductVariantAttribute($this);
    }
}
