<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Categories\Models;

use Baka\Support\Str;
use Canvas\Contracts\FileSystemModelTrait;
use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\ProductCategories as ProductCategory;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\ProductCategory as DomainProductCategory;
use Kanvas\Inventory\Traits\Publishable;

class Categories extends BaseModel
{
    use Publishable;
    use FileSystemModelTrait;

    public int $apps_id;
    public int $companies_id;
    public string $uuid;
    public string $name;
    public string $slug;
    public ?string $code = null;
    public int $is_default = State::IS_DEFAULT;
    public int $position = 0;
    public int $parent_id = 0;
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

        $this->setSource('categories');

        $this->belongsTo(
            'parent_id',
            self::class,
            'id',
            [
                'alias' => 'parent',
                'reusable' => true
            ]
        );
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
        }
    }

    /**
     * Set category parent.
     *
     * @param Categories $parent
     *
     * @return void
     */
    public function setParent(Categories $parent) : void
    {
        $this->parent_id = $parent->getId();
        $this->saveOrFail();
    }

    /**
     * Add a child to this category.
     *
     * @param Categories $child
     *
     * @return void
     */
    public function addChild(Categories $child) : void
    {
        $child->parent_id = $this->getId();
        $child->saveOrFail();
    }

    /**
     * Add a product to a specify category.
     *
     * @param Products $product
     *
     * @return ProductCategory
     */
    public function addProduct(Products $product) : ProductCategory
    {
        $productCategory = new DomainProductCategory($product);

        return $productCategory->add($this);
    }
}
