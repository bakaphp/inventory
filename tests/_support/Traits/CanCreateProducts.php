<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Support\Traits;

use IntegrationTester;
use Kanvas\Inventory\Categories\Category;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\Product;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Tests\Support\Models\Users;

trait CanCreateProducts
{
    /**
     * Create a category.
     *
     * @return Products
     */
    protected function createProduct(IntegrationTester $I) : Products
    {
        $user = new Users();
        $region = Regions::findFirst();

        $product = Product::create(
            $user,
            $I->faker()->name(),
            Categories::findFirstOrFail(),
            [
                'is_published' => State::PUBLISHED,
                'description' => $I->faker()->text(),
                'short_description' => $I->faker()->text(),
            ]
        );

        return $product;
    }
}
