<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Categories;

use IntegrationTester;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateRegion;

class RegionsCest
{
    use CanCreateRegion;


    public function tesCreate(IntegrationTester $I) : void
    {
        $user = new Users();

        $regions = $this->createRegion($I);

        $I->assertInstanceOf(Regions::class, $regions);
    }
}
