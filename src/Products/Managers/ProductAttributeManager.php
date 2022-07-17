<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Managers;

use Kanvas\Inventory\Attributes\Models\Attributes as ModelsAttributes;
use Kanvas\Inventory\Contracts\ManagerInterface;
use Kanvas\Inventory\Products\Models\ProductAttributes;

use Kanvas\Inventory\Products\Models\Products;

class ProductAttributeManager implements ManagerInterface
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
     * Add attributes to a product.
     *
     * @param ModelsAttributes $attribute
     * @param mixed $value
     *
     * @return ProductAttributes
     */
    public function add(ModelsAttributes $attribute, $value) : ProductAttributes
    {
        return ProductAttributes::findFirstOrCreate([
            'conditions' => 'products_id = :products_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ], [
            'attributes_id' => $attribute->getId(),
            'products_id' => $this->product->getId(),
            'value' => !is_array($value) ? $value : json_encode($value),
        ]);
    }

    /**
     * Add multiped attributes.
     *
     * @param array $attributes<int, <'attribute' => Attributes, 'value' => string>>
     *
     * @return array<int, ProductAttributes>
     */
    public function addMultiple(array $attributes) : array
    {
        $results = [];
        foreach ($attributes as $attribute) {
            if (!isset($attribute['attribute']) || !isset($attribute['value'])) {
                continue;
            }

            $results[] = $this->add(
                $attribute['attribute'],
                $attribute['value'],
            );
        }

        return $results;
    }

    /**
     * update attributes to a product.
     *
     * @param ModelsAttributes $attribute
     * @param mixed $value
     *
     * @return ProductAttributes
     */
    public function update(ModelsAttributes $attribute, $value) : ProductAttributes
    {
        return ProductAttributes::updateOrCreate([
            'conditions' => 'products_id = :products_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ], [
            'attributes_id' => $attribute->getId(),
            'products_id' => $this->product->getId(),
            'value' => !is_array($value) ? $value : json_encode($value),
        ]);
    }

    /**
     * Remove attribute from product.
     *
     * @param ModelsAttributes $attribute
     *
     * @return bool
     */
    public function delete(ModelsAttributes $attribute) : bool
    {
        $productAttribute = ProductAttributes::findFirst([
            'conditions' => 'products_id = :products_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ]);

        if ($productAttribute) {
            return $productAttribute->delete();
        }

        return false;
    }
}
