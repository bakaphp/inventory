<?php

declare(strict_types=1);

namespace Kanvas\Guild\Tests\Integration\Categories;

use IntegrationTester;
use Kanvas\Inventory\Categories\Category;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;
use Kanvas\Inventory\Warehouses\Warehouse;

class WarehouseCest
{
    /**
     * Create a category.
     *
     * @return Category
     */
    protected function createWarehouse(IntegrationTester $I) : ModelsWarehouse
    {
        $user = new Users();
        $region = Regions::findFirst();

        $warehouse = Warehouse::create(
            $user,
            $I->faker()->name(),
            $region,
            [
                'is_published' => State::PUBLISHED,
            ]
        );

        return $warehouse;
    }

    public function tesCreate(IntegrationTester $I) : void
    {
        $user = new Users();

        $warehouse = $this->createWarehouse($I);

        $I->assertInstanceOf(ModelsWarehouse::class, $warehouse);
    }

    public function testUpdateCategory(IntegrationTester $I) : void
    {
        $user = new Users();

        $warehouse = $this->createWarehouse($I);

        $newName = $I->faker()->name();
        $warehouse->name = $newName;
        $warehouse->saveOrFail();

        $I->assertEquals($warehouse->name, $newName);
    }

    public function testGetById(IntegrationTester $I) : void
    {
        $user = new Users();

        $warehouse = $this->createWarehouse($I);

        $warehouse = Warehouse::getById($warehouse->getId(), $user);

        $I->assertInstanceOf(ModelsWarehouse::class, $warehouse);
    }

    public function testGetByUuid(IntegrationTester $I) : void
    {
        $user = new Users();

        $warehouse = Warehouse::getAll($user);

        $warehouse = Warehouse::getByUuid($warehouse->getFirst()->uuid, $user);

        $I->assertInstanceOf(ModelsWarehouse::class, $warehouse);
    }

    public function testGetAll(IntegrationTester $I) : void
    {
        $user = new Users();

        $warehouses = Warehouse::getAll($user);
        $warehousesSecond = Warehouse::getAll($user, 1, 1);

        $I->assertTrue($warehouses->count() > 0);
        $I->assertTrue($warehousesSecond->count() === 1);
    }

    public function testPublish(IntegrationTester $I) : void
    {
        $user = new Users();

        $warehouse = $this->createWarehouse($I);

        $warehouse->publish();

        $I->assertEquals($warehouse->isPublished(), State::PUBLISHED);
    }

    public function testUnPublish(IntegrationTester $I) : void
    {
        $user = new Users();

        $warehouse = $this->createWarehouse($I);

        $warehouse->unPublish();

        $I->assertEquals($warehouse->isPublished(), State::UN_PUBLISHED);
    }
}
