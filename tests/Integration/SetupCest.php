<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Categories;

use Canvas\Models\Apps;
use Canvas\Models\Companies;
use IntegrationTester;
use Kanvas\Inventory\Setup;
use Kanvas\Inventory\Tests\Support\Models\Users;

class SetupCest
{
    public function testRun(IntegrationTester $I) : void
    {
        $user = new Users();
        $company = new Companies();
        $company->id = 1;

        $app = new Apps();
        $app->id = 1;
        $setup = new Setup($user, $company, $app);

        $I->assertTrue($setup->run());
    }
}
