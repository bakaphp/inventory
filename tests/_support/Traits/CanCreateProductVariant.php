<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Support\Traits;

use IntegrationTester;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Variants\Models\ProductVariants;
use Phalcon\Utils\Slug;

trait CanCreateProductVariant
{
    use CanCreateProducts;

    /**
     * Create Product Variant.
     *
     * @param IntegrationTester $I
     *
     * @return void
     */
    public function createProductVariant(IntegrationTester $I) : ProductVariants
    {
        $product = $this->createProduct($I);
        $user = new Users();
        $name = $I->faker()->name;
        $description = $I->faker()->name;
        $sku = Slug::generate($I->faker()->name);


        return $product->variant()->add(
            $user,
            $name,
            $sku,
            $description,
            []
        );
    }
}
