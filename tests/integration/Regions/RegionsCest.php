<?php

declare(strict_types=1);

namespace Kanvas\Guild\Tests\Integration\Categories;

use Canvas\Models\Currencies;
use IntegrationTester;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Regions\Region;
use Kanvas\Inventory\Tests\Support\Models\Users;

class RegionsCest
{
    /**
     * Create a Regions.
     *
     * @return Regions
     */
    protected function createRegion(IntegrationTester $I) : Regions
    {
        $user = new Users();
        $region = Regions::findFirst();
        $currency = Currencies::findFirst();

        $regions = Region::create(
            $user,
            $I->faker()->name(),
            $currency,
            [
                'is_published' => State::PUBLISHED,
                'is_default' => State::DEFAULT,
            ]
        );

        return $regions;
    }

    public function tesCreate(IntegrationTester $I) : void
    {
        $user = new Users();

        $regions = $this->createRegion($I);

        $I->assertInstanceOf(Regions::class, $regions);
    }
}
