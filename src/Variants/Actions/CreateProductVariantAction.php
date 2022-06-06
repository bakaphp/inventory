<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants\Actions;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Variants\Models\ProductVariants as ModelsProductVariant;

class CreateProductVariantAction
{
    /**
     * Create new product variant.
     *
     * @param Products $product
     * @param UserInterface $user
     * @param string $name
     * @param string $sku
     * @param string|null $description
     * @param array $options
     *
     * @return ModelsProductVariant
     */
    public static function execute(
        Products $product,
        UserInterface $user,
        string $name,
        string $sku,
        string $description = null,
        array $options = []
    ) : ModelsProductVariant {
        $variant = new ModelsProductVariant();
        $variant->users_id = $user->getId();
        $variant->products_id = $product->getId();
        $variant->name = $name;
        $variant->sku = $sku;
        $variant->description = $description;
        $variant->is_published = isset($options['is_published']) ? (int) $options['is_published'] : State::PUBLISHED;
        $variant->short_description = $options['short_description'] ?? null;
        $variant->ean = $options['ean'] ?? null;
        $variant->serial_number = $options['serial_number'] ?? null;
        $variant->barcode = $options['barcode'] ?? null;
        $variant->slug = $options['slug'] ?? '';
        $variant->position = isset($options['position']) && (int) $options['position'] > 0 ? $options['position'] : State::DEFAULT_POSITION;
        $variant->saveOrFail();

        return $variant;
    }
}
