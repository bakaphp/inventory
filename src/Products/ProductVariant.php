<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Variants\Models\ProductVariants;
use Kanvas\Inventory\Variants\ProductVariant as DomainProductVariant;

class ProductVariant
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

    public function add(
        UserInterface $user,
        string $name,
        string $sku,
        string $description = null,
        array $options = []
    ) : ProductVariants {
        return DomainProductVariant::create(
            $this->product,
            $user,
            $name,
            $sku,
            $description,
            $options
        );
    }
}
