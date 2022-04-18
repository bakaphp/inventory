<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use Canvas\Models\Apps;
use Canvas\Models\Companies;
use Canvas\Models\SystemModules;
use IntegrationTester;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Actions\ImportProductsAction;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Tests\Support\DataExporters\Vehicles;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateRegion;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateWarehouse;
use Kanvas\Inventory\Variants\Models\ProductVariants;
use Kanvas\Inventory\Warehouses\Actions\CreateWarehouseAction;
use Kanvas\Inventory\Warehouses\Models\Warehouses;

class ProductsImportCest
{
    use CanCreateWarehouse;
    use CanCreateRegion;

    public function testImport(IntegrationTester $I) : void
    {
        $user = new Users();
        $company = new Companies();
        $company->id = 1;

        $region = $this->createRegion($I);

        $app = new Apps();
        $app->id = 1;

        $warehouse = CreateWarehouseAction::execute(
            $user,
            $I->faker()->name(),
            $region,
            [
                'is_published' => State::PUBLISHED,
                'is_default' => State::DEFAULT
            ]
        );

        SystemModules::createForApp(Products::class, $app);
        SystemModules::createForApp(ProductVariants::class, $app);
        SystemModules::createForApp(Warehouses::class, $app);

        $importProduct = new ImportProductsAction(
            $user,
            $company,
            $app
        );

        $exportedVehicles = new Vehicles();
        $productsVariants = $importProduct->execute($exportedVehicles);

        $I->assertNotEmpty($productsVariants);
        foreach ($productsVariants as $variant) {
            $I->assertInstanceOf(ProductVariants::class, $variant);
        }
    }
}
