<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\Database\ModelInterface;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\Models\Variants as ModelsProductVariant;
use Kanvas\Inventory\Traits\Searchable;

class Variant
{
    use Searchable;

    /**
     * Get model.
     *
     * @return ModelInterface
     */
    public static function getModel() : ModelInterface
    {
        return new ModelsProductVariant();
    }

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
    public static function create(
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
        $variant->position = isset($options['position']) && (int) $options['position'] > 0 ? $options['position'] : State::DEFAULT_POSITION;
        $variant->saveOrFail();

        return $variant;
    }

    /**
     *  get by sku.
     *
     * @param string $uui
     * @param UserInterface $user
     *
     * @return ModelInterface
     */
    public static function getBySku(string $sku, UserInterface $user) : ModelInterface
    {
        $model = self::getModel();

        return $model::findFirstOrFail([
            'conditions' => 'sku = :sku: 
                            AND companies_id = :companies_id: AND is_deleted = 0',
            'bind' => [
                'sku' => $sku,
                'companies_id' => $user->currentCompanyId(),
            ]
        ]);
    }
}
