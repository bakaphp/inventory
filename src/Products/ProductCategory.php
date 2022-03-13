<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products;

use Kanvas\Inventory\Categories\Models\Categories as ModelsCategories;
use Kanvas\Inventory\Products\Models\Categories as ModelProductCategory;
use Kanvas\Inventory\Products\Models\Products;

class ProductCategory
{
    protected Products $product;

    /**
     * Constructor.
     *
     * @param Products $product
     */
    public function __construct(Products $product)
    {
        $this->product = $product;
    }

    /**
     * Add a product to a specify category.
     *
     * @param ModelsCategories $category
     *
     * @return ModelProductCategory
     */
    public function add(ModelsCategories $category) : ModelProductCategory
    {
        return ModelProductCategory::findFirstOrCreate([
            'conditions' => 'products_id = :products_id: AND categories_id = :categories_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
                'categories_id' => $category->getId(),
            ]
        ], [
            'categories_id' => $category->getId(),
            'products_id' => $this->product->getId(),
        ]);
    }

    /**
     * Add a product to a specify category.
     *
     * @param Categories $category<int, Categories>
     *
     * @return array <int, ProductCategory>
     */
    public function addMultiple(array $categories) : array
    {
        $results = [];
        foreach ($categories as $category) {
            $results[] = $this->add($category);
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
    public function delete(ModelsCategories $category) : bool
    {
        $productCategory = ModelProductCategory::findFirst([
            'conditions' => 'products_id = :products_id: AND categories_id = :categories_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
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
    public function move(ModelsCategories $category, ModelsCategories $newCategory) : bool
    {
        $productCategory = ModelProductCategory::findFirst([
            'conditions' => 'products_id = :products_id: AND categories_id = :categories_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
                'categories_id' => $category->getId(),
            ]
        ]);

        if ($productCategory) {
            $productCategory->categories_id = $newCategory->getId();
            return $productCategory->save();
        }

        return false;
    }
}
