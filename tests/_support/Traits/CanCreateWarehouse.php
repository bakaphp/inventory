<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Support\Traits;

use IntegrationTester;
use Kanvas\Inventory\Categories\Category;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Warehouses\Models\Warehouses;
use Kanvas\Inventory\Warehouses\Warehouse;

trait CanCreateWarehouse
{
    use CanCreateRegion;

    /**
     * Create a category.
     *
     * @return Category
     */
    protected function createWarehouse(IntegrationTester $I) : Warehouses
    {
        $user = new Users();
        $region = $this->createRegion($I);

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
}
