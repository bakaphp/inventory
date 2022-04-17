<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use IntegrationTester;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\Repositories\ProductRepository;
use Kanvas\Inventory\Products\Repositories\ProductWarehouseRepository;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateProducts;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateRegion;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateWarehouse;
use Kanvas\Inventory\Warehouses\Actions\CreateWarehouseAction;

class ProductsWarehouseCest
{
    use CanCreateProducts;
    use CanCreateRegion;
    use CanCreateWarehouse;

    public function testAddWarehouse(IntegrationTester $I)
    {
        $user = new Users();

        $product = ProductRepository::getAll($user)->getFirst();
        $newName = $I->faker()->name();
        $region = $this->createRegion($I);

        $warehouse = CreateWarehouseAction::execute(
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

        $product = ProductRepository::getAll($user)->getFirst();
        $newName = $I->faker()->name();
        $newNameTwo = $I->faker()->name();
        $region = Regions::findFirst();

        $warehouse = CreateWarehouseAction::execute(
            $user,
            $newName,
            $region,
            [
                'is_published' => State::PUBLISHED,
            ]
        );
        $warehouseTwo = CreateWarehouseAction::execute(
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

        $product = ProductRepository::getAll($user)->getFirst();
        $newName = $I->faker()->name();
        $region = Regions::findFirst();

        $warehouse = CreateWarehouseAction::execute(
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
        $product = ProductRepository::getAll($user)->getFirst();
        $productWarehouse = $product->warehouse()->add($warehouse);

        $products = ProductWarehouseRepository::getAll($user, $warehouse);

        $I->assertEquals(1, $products->count());
    }

    public function testGetByUuid(IntegrationTester $I) : void
    {
        $warehouse = $this->createWarehouse($I);

        $user = new Users();
        $product = ProductRepository::getAll($user)->getFirst();
        $productWarehouse = $product->warehouse()->add($warehouse);

        $products = ProductWarehouseRepository::getByUuid($product->uuid, $user, $warehouse);

        $I->assertEquals(Products::class, get_class($products));
    }
}
