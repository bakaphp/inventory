<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products;

use Kanvas\Inventory\Attributes\Models\Attributes as ModelsAttributes;
use Kanvas\Inventory\Products\Models\Attributes;
use Kanvas\Inventory\Products\Models\Products;

class ProductAttributes
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
     * @param string $value
     *
     * @return Attributes
     */
    public function add(ModelsAttributes $attribute, string $value) : Attributes
    {
        return Attributes::findFirstOrCreate([
            'conditions' => 'products_id = :products_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ], [
            'attributes_id' => $attribute->getId(),
            'products_id' => $this->product->getId(),
            'value' => $value,
        ]);
    }

    /**
     * Add multiped attributes.
     *
     * @param array $attributes<int, <'attribute' => Attributes, 'value' => string>>
     *
     * @return array<int, ModelsAttributes>
     */
    public function addMultiple(array $attributes) : array
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
     * @param ModelsAttributes $attribute
     * @param string $value
     *
     * @return Attributes
     */
    public function update(ModelsAttributes $attribute, string $value) : Attributes
    {
        return Attributes::updateOrCreate([
            'conditions' => 'products_id = :products_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_id' => $this->product->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ], [
            'attributes_id' => $attribute->getId(),
            'products_id' => $this->product->getId(),
            'value' => $value,
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
        $productAttribute = Attributes::findFirst([
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
