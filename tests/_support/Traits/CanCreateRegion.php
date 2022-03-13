<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Support\Traits;

use Canvas\Models\Currencies;
use IntegrationTester;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Regions\Region;
use Kanvas\Inventory\Tests\Support\Models\Users;

trait CanCreateRegion
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
}
