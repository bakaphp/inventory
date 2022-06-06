<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Variants\Repositories;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\Database\ModelInterface;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Traits\Searchable;
use Kanvas\Inventory\Variants\Models\ProductVariants as ModelsProductVariant;

class ProductVariantRepository
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
     *  get by sku.
     *
     * @param string $uui
     * @param Products $product
     *
     * @return ModelInterface
     */
    public static function getBySku(string $sku, Products $product) : ModelInterface
    {
        $model = self::getModel();

        return $model::findFirstOrFail([
            'conditions' => 'sku = :sku: 
                            AND products_id = :product_id: AND is_deleted = 0',
            'bind' => [
                'sku' => $sku,
                'product_id' => $product->getId(),
            ]
        ]);
    }

    /**
     *  Get a domain data by its uuid.
     *
     * @param string $uui
     * @param UserInterface $user
     *
     * @return ModelInterface
     */
    public static function getByUuid(string $uuid, UserInterface $user) : ModelInterface
    {
        $model = self::getModel();

        return $model::findByRawSql(
            'SELECT 
            v.*
            FROM 
                products p,
                products_variants v
            WHERE
                v.products_id = p.id
                AND p.companies_id = ?
                AND v.uuid = ?
                AND v.is_deleted = p.is_deleted
                AND v.is_deleted = 0
            LIMIT 1',
            [
                $user->currentCompanyId(),
                $uuid
            ]
        )->getFirst();
    }
}
