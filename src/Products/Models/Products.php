<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Models;

use Baka\Support\Str;
use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\Attributes\Models\Attributes as ModelsAttributes;
use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Categories\Models\Categories as ModelsCategories;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Categories as ProductCategory;
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
     * @return ProductCategory
     */
    public function addCategory(ModelsCategories $category) : ProductCategory
    {
        return ProductCategory::findFirstOrCreate([
            'conditions' => 'products_id = :products_id: AND categories_id = :categories_id:',
            'bind' => [
                'products_id' => $this->getId(),
                'categories_id' => $category->getId(),
            ]
        ], [
            'categories_id' => $category->getId(),
            'products_id' => $this->getId(),
        ]);
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
        $results = [];
        foreach ($categories as $category) {
            $results[] = $this->addCategory($category);
        }

        return $results;
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
        $productCategory = ProductCategory::findFirst([
            'conditions' => 'products_id = :products_id: AND categories_id = :categories_id:',
            'bind' => [
                'products_id' => $this->getId(),
                'categories_id' => $category->getId(),
            ]
        ]);

        if ($productCategory) {
            return $productCategory->delete();
        }

        return false;
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
        $productCategory = ProductCategory::findFirst([
            'conditions' => 'products_id = :products_id: AND categories_id = :categories_id:',
            'bind' => [
                'products_id' => $this->getId(),
                'categories_id' => $category->getId(),
            ]
        ]);

        if ($productCategory) {
            $productCategory->categories_id = $newCategory->getId();
            return $productCategory->save();
        }

        return false;
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
     * @param Attributes $attribute
     * @param string $value
     *
     * @return Attributes
     */
    public function addAttribute(Attributes $attribute, string $value) : Attributes
    {
        return Attributes::findFirstOrCreate([
            'conditions' => 'products_id = :products_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_id' => $this->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ], [
            'attributes_id' => $attribute->getId(),
            'products_id' => $this->getId(),
            'value' => $value,
        ]);
    }

    /**
     * Add multiped attributes.
     *
     * @param array $attributes<int, <'attribute' => Attributes, 'value' => string>>
     *
     * @return array<int, Attributes>
     */
    public function addAttributes(array $attributes) : array
    {
        $results = [];
        foreach ($attributes as $attribute) {
            $results[] = $this->addAttribute(
                $attribute['attribute'],
                $attribute['value']
            );
        }

        return $results;
    }

    /**
     * update attributes to a product.
     *
     * @param Attributes $attribute
     * @param string $value
     *
     * @return Attributes
     */
    public function updateAttribute(Attributes $attribute, string $value) : Attributes
    {
        return Attributes::updateOrCreate([
            'conditions' => 'products_id = :products_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_id' => $this->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ], [
            'attributes_id' => $attribute->getId(),
            'products_id' => $this->getId(),
            'value' => $value,
        ]);
    }

    /**
     * Remove attribute from product.
     *
     * @param Attributes $attribute
     *
     * @return bool
     */
    public function removeAttribute(Attributes $attribute) : bool
    {
        $productAttribute = Attributes::findFirst([
            'conditions' => 'products_id = :products_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_id' => $this->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ]);

        if ($productAttribute) {
            return $productAttribute->delete();
        }

        return false;
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
        return Warehouse::findFirstOrCreate([
            'conditions' => 'products_id = :products_id: AND warehouses_id = :warehouses_id:',
            'bind' => [
                'products_id' => $this->getId(),
                'warehouses_id' => $warehouse->getId(),
            ]
        ], [
            'warehouses_id' => $warehouse->getId(),
            'products_id' => $this->getId(),
            'is_published' => $isPublished,
            'rating' => $rating,
        ]);
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
        $results = [];
        foreach ($warehouses as $warehouse) {
            $results[] = $this->addWarehouse($warehouse);
        }

        return $results;
    }
}
