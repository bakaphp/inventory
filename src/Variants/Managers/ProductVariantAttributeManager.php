<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants\Managers;

use Kanvas\Inventory\Attributes\Models\Attributes as ModelsAttributes;
use Kanvas\Inventory\Contracts\ManagerInterface;
use Kanvas\Inventory\Variants\Models\ProductVariantAttributes;
use Kanvas\Inventory\Variants\Models\ProductVariants;

class ProductVariantAttributeManager implements ManagerInterface
{
    protected ProductVariants $productVariant;

    /**
     * Constructor.
     *
     * @param Products $product
     */
    public function __construct(ProductVariants $productVariant)
    {
        $this->productVariant = $productVariant;
    }

    /**
     * Add attributes to product variants.
     *
     * @param ModelsAttributes $attribute
     * @param mixed $value
     *
     * @return ProductVariantAttributes
     */
    public function add(ModelsAttributes $attribute, $value) : ProductVariantAttributes
    {
        return ProductVariantAttributes::findFirstOrCreate([
            'conditions' => 'products_variants_id = :products_variants_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_variants_id' => $this->productVariant->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ], [
            'attributes_id' => $attribute->getId(),
            'products_variants_id' => $this->productVariant->getId(),
            'value' => !is_array($value) ? $value : json_encode($value),
        ]);
    }

    /**
     * Add multiped attributes.
     *
     * @param array $attributes<int, <'attribute' => Attributes, 'value' => string>>
     *
     * @return array<int, ProductVariantAttributes>
     */
    public function addMultiple(array $attributes) : array
    {
        $results = [];
        foreach ($attributes as $attribute) {
            $results[] = $this->add(
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
     * @param mixed $value
     *
     * @return ProductVariantAttributes
     */
    public function update(ModelsAttributes $attribute, $value) : ProductVariantAttributes
    {
        return ProductVariantAttributes::updateOrCreate([
            'conditions' => 'products_variants_id = :products_variants_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_variants_id' => $this->productVariant->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ], [
            'attributes_id' => $attribute->getId(),
            'products_variants_id' => $this->productVariant->getId(),
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
        $productAttribute = ProductVariantAttributes::findFirst([
            'conditions' => 'products_variants_id = :products_variants_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_variants_id' => $this->productVariant->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ]);

        if ($productAttribute) {
            return $productAttribute->delete();
        }

        return false;
    }
}
