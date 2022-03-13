<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Models;

use Baka\Support\Str;
use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\Attributes\Models\Attributes as ModelsAttributes;
use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Categories\Models\Categories as ModelsCategories;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Category;
use Kanvas\Inventory\Products\Models\Categories as ModelProductCategory;
use Kanvas\Inventory\Products\ProductAttributes;
use Kanvas\Inventory\Products\ProductCategory;
use Kanvas\Inventory\Products\ProductWarehouse;
use Kanvas\Inventory\Traits\Publishable;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;
use Phalcon\Mvc\Model\ResultsetInterface;

class Products extends BaseModel
{
    use Publishable;

    public int $apps_id;
    public int $companies_id;
    public string $uuid;
    public string $name;
    public string $slug;
    public ?string $description = null;
    public ?string $short_description = null;
    public ?string $warranty_terms = null;
    public ?string $upc = null;
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

        $this->setSource('products');

        $this->hasMany(
            'id',
            Variants::class,
            'products_id',
            [
                'alias' => 'variants',
                'reusable' => true
            ]
        );

        $this->hasManyToMany(
            'id',
            Attributes::class,
            'products_id',
            'attributes_id',
            ModelsAttributes::class,
            'id',
            [
                'alias' => 'attributes',
                'elasticIndex' => false,
                'reusable' => true,
            ]
        );

        $this->hasManyToMany(
            'id',
            Categories::class,
            'products_id',
            'categories_id',
            ModelsCategories::class,
            'id',
            [
                'alias' => 'categories',
                'elasticIndex' => false,
                'reusable' => true,
            ]
        );

        $this->hasManyToMany(
            'id',
            Warehouse::class,
            'products_id',
            'warehouse_id',
            ModelsWarehouse::class,
            'id',
            [
                'alias' => 'warehouses',
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
     * Add a product to a specify category.
     *
     * @param ModelsCategories $category
     *
     * @return ModelProductCategory
     */
    public function addCategory(ModelsCategories $category) : ModelProductCategory
    {
        $productCategory = new ProductCategory($this);
        return $productCategory->add($category);
    }

    /**
     * Add a product to a specify category.
     *
     * @param Categories $category<int, Categories>
     *
     * @return array <int, ProductCategory>
     */
    public function addCategories(array $categories) : array
    {
        $productCategory = new ProductCategory($this);
        return $productCategory->addMultiple($categories);
    }

    /**
     * Remove product from category.
     *
     * @param ModelsCategories $category
     *
     * @return bool
     */
    public function removeCategory(ModelsCategories $category) : bool
    {
        $productCategory = new ProductCategory($this);
        return $productCategory->delete($category);
    }

    /**
     * Move product to a new category.
     *
     * @param ModelsCategories $category
     * @param ModelsCategories $newCategory
     *
     * @return bool
     */
    public function moveCategory(ModelsCategories $category, ModelsCategories $newCategory) : bool
    {
        $productCategory = new ProductCategory($this);
        return $productCategory->move($category, $newCategory);
    }

    /**
     * Get categories.
     *
     * @return ResultsetInterface <Categories>
     */
    public function getCategories() : ResultsetInterface
    {
        return $this->categories;
    }

    /**
     * Get Attributes.
     *
     * @return ResultsetInterface <Attributes>
     */
    public function getAttributes() : ResultsetInterface
    {
        return $this->attributes;
    }

    /**
     * Get warehouse.
     *
     * @return ResultsetInterface <ModelsWarehouse>
     */
    public function getWarehouse() : ResultsetInterface
    {
        return $this->warehouses;
    }

    /**
     * Add attributes to a product.
     *
     * @param ModelsAttributes $attribute
     * @param string $value
     *
     * @return Attributes
     */
    public function addAttribute(ModelsAttributes $attribute, string $value) : Attributes
    {
        $productAttribute = new ProductAttributes($this);
        return $productAttribute->add($attribute, $value);
    }

    /**
     * Add multiped attributes.
     *
     * @param array $attributes<int, <'attribute' => ModelsAttributes, 'value' => string>>
     *
     * @return array<int, ModelsAttributes>
     */
    public function addAttributes(array $attributes) : array
    {
        $productAttribute = new ProductAttributes($this);
        return $productAttribute->addMultiple($attributes);
    }

    /**
     * update attributes to a product.
     *
     * @param ModelsAttributes $attribute
     * @param string $value
     *
     * @return Attributes
     */
    public function updateAttribute(ModelsAttributes $attribute, string $value) : Attributes
    {
        $productAttribute = new ProductAttributes($this);
        return $productAttribute->update($attribute, $value);
    }

    /**
     * Remove attribute from product.
     *
     * @param ModelsAttributes $attribute
     *
     * @return bool
     */
    public function removeAttribute(ModelsAttributes $attribute) : bool
    {
        $productAttribute = new ProductAttributes($this);
        return $productAttribute->delete($attribute);
    }

    /**
     * Add warehouse for this product.
     *
     * @param ModelsWarehouse $warehouse
     * @param int $isPublished
     * @param int $rating
     *
     * @return ModelsWarehouse
     */
    public function addWarehouse(ModelsWarehouse $warehouse, int $isPublished = State::PUBLISHED, int $rating = 0) : ModelsWarehouse
    {
        $productWarehouse = new ProductWarehouse($this);
        return $productWarehouse->add($warehouse, $isPublished, $rating);
    }

    /**
     * Add multiped warehouses.
     *
     * @param array $warehouses<int, ModelsWarehouse>
     *
     * @return array<int, ModelsWarehouse>
     */
    public function addWarehouses(array $warehouses) : array
    {
        $productWarehouse = new ProductWarehouse($this);
        return $productWarehouse->addMultiples($warehouses);
    }
}
