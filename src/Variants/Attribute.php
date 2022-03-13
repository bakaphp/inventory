<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants;

use Kanvas\Inventory\Attributes\Models\Attributes as ModelsAttributes;
use Kanvas\Inventory\Products\Models\Variants as ModelProductVariant;
use Kanvas\Inventory\Variants\Models\Attributes as ModelsProductVariantAttributes;

class Attribute
{
    protected ModelProductVariant $productVariant;

    /**
     * Constructor.
     *
     * @param Products $product
     */
    public function __construct(ModelProductVariant $productVariant)
    {
        $this->productVariant = $productVariant;
    }

    /**
     * Add attributes to product variants.
     *
     * @param ModelsAttributes $attribute
     * @param string $value
     *
     * @return ModelsProductVariantAttributes
     */
    public function add(ModelsAttributes $attribute, string $value) : ModelsProductVariantAttributes
    {
        return ModelsProductVariantAttributes::findFirstOrCreate([
            'conditions' => 'products_variants_id = :products_variants_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_variants_id' => $this->productVariant->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ], [
            'attributes_id' => $attribute->getId(),
            'products_variants_id' => $this->productVariant->getId(),
            'value' => $value,
        ]);
    }

    /**
     * Add multiped attributes.
     *
     * @param array $attributes<int, <'attribute' => Attributes, 'value' => string>>
     *
     * @return array<int, ModelsProductVariantAttributes>
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
     * @param string $value
     *
     * @return ModelsProductVariantAttributes
     */
    public function update(ModelsAttributes $attribute, string $value) : ModelsProductVariantAttributes
    {
        return ModelsProductVariantAttributes::updateOrCreate([
            'conditions' => 'products_variants_id = :products_variants_id: AND attributes_id = :attributes_id:',
            'bind' => [
                'products_variants_id' => $this->productVariant->getId(),
                'attributes_id' => $attribute->getId(),
            ]
        ], [
            'attributes_id' => $attribute->getId(),
            'products_variants_id' => $this->productVariant->getId(),
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
        $productAttribute = ModelsProductVariantAttributes::findFirst([
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
