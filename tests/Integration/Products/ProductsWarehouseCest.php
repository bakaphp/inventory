<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use IntegrationTester;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\Product;
use Kanvas\Inventory\Products\ProductWarehouse;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateProducts;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateRegion;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateWarehouse;
use Kanvas\Inventory\Warehouses\Warehouse;

class ProductsWarehouseCest
{
    use CanCreateProducts;
    use CanCreateRegion;
    use CanCreateWarehouse;

    public function testAddWarehouse(IntegrationTester $I)
    {
        $user = new Users();

        $product = Product::getAll($user)->getFirst();
        $newName = $I->faker()->name();
        $region = $this->createRegion($I);

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

    public function testAddMultipleWarehouse(IntegrationTester $I)
    {
        $user = new Users();

        $product = Product::getAll($user)->getFirst();
        $newName = $I->faker()->name();
        $newNameTwo = $I->faker()->name();
        $region = Regions::findFirst();

        $warehouse = Warehouse::create(
            $user,
            $newName,
            $region,
            [
                'is_published' => State::PUBLISHED,
            ]
        );
        $warehouseTwo = Warehouse::create(
            $user,
            $newNameTwo,
            $region,
            [
                'is_published' => State::PUBLISHED,
            ]
        );

        $productWarehouse = $product->warehouse()->addMultiples([$warehouse, $warehouseTwo]);

        $I->assertEquals($productWarehouse[0]->warehouse->name, $newName);
        $I->assertEquals($productWarehouse[1]->warehouse->name, $newNameTwo);
    }

    public function testDeleteWarehouse(IntegrationTester $I)
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

        $I->assertTrue($product->warehouse()->delete($warehouse));
    }

    public function testGetAll(IntegrationTester $I) : void
    {
        $warehouse = $this->createWarehouse($I);

        $user = new Users();
        $product = Product::getAll($user)->getFirst();
        $productWarehouse = $product->warehouse()->add($warehouse);

        $products = ProductWarehouse::getAll($user, $warehouse);

        $I->assertEquals(1, $products->count());
    }

    public function testGetByUuid(IntegrationTester $I) : void
    {
        $warehouse = $this->createWarehouse($I);

        $user = new Users();
        $product = Product::getAll($user)->getFirst();
        $productWarehouse = $product->warehouse()->add($warehouse);

        $products = ProductWarehouse::getByUuid($product->uuid, $user, $warehouse);

        $I->assertEquals(Products::class, get_class($products));
    }
}
