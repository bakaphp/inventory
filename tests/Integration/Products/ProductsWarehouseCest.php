<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use IntegrationTester;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Product;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateProducts;
use Kanvas\Inventory\Warehouses\Warehouse;

class ProductsWarehouseCest
{
    use CanCreateProducts;

    public function testAddWarehouse(IntegrationTester $I)
    {
        $user = new Users();

        $product = Product::getAll($user)->getFirst();
        $newName = $I->faker()->name();
        $region = Regions::findFirst();

        $warehouse = Warehouse::create(
            $user,
            $newName,
            $region,
            [
                'is_published' => State::PUBLISHED,
            ]
        );

        $productWarehouse = $product->warehouse()->add($warehouse);

        $I->assertEquals($productWarehouse->warehouse->name, $newName);
    }
}
