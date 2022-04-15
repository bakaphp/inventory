<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Managers;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Inventory\Contracts\ManagerInterface;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Variants\Actions\CreateProductVariantAction;
use Kanvas\Inventory\Variants\Models\ProductVariants;

class ProductVariantManager implements ManagerInterface
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
     * Add a new variant product.
     *
     * @param UserInterface $user
     * @param string $name
     * @param string $sku
     * @param string|null $description
     * @param array $options
     *
     * @return ProductVariants
     */
    public function add(
        UserInterface $user,
        string $name,
        string $sku,
        string $description = null,
        array $options = []
    ) : ProductVariants {
        return CreateProductVariantAction::execute(
            $this->product,
            $user,
            $name,
            $sku,
            $description,
            $options
        );
    }
}
