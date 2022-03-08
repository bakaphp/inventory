<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Models;

use Baka\Support\Str;
use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\Attributes\Models\Attributes as ModelsAttributes;
use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Categories\Models\Categories as ModelsCategories;
use Kanvas\Inventory\Products\Models\Categories as ProductCategory;
use Kanvas\Inventory\Traits\Publishable;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;

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
     * @param Categories $category
     *
     * @return ProductCategory
     */
    public function addCategory(Categories $category) : ProductCategory
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
}
