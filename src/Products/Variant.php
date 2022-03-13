<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\Models\Variants as ModelProductVariant;
use Kanvas\Inventory\Variants\Variant as ProductVariant;

class Variant
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
    ) : ModelProductVariant {
        return ProductVariant::create(
            $this->product,
            $user,
            $name,
            $sku,
            $description,
            $options
        );
    }
}
