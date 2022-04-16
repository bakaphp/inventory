<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use Canvas\Models\Apps;
use Canvas\Models\Companies;
use IntegrationTester;
use Kanvas\Inventory\Products\Actions\ImportProductsAction;
use Kanvas\Inventory\Tests\Support\DataExporters\Vehicles;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Variants\Models\ProductVariants;

class ProductsImportCest
{
    public function testImport(IntegrationTester $I) : void
    {
        $user = new Users();
        $company = new Companies();
        $company->id = 1;

        $importProduct = new ImportProductsAction(
            $user,
            $company,
            new Apps()
        );

        $exportedVehicles = new Vehicles();
        $productsVariants = $importProduct->execute($exportedVehicles);

        $I->assertNotEmpty($productsVariants);
        foreach ($productsVariants as $variant) {
            $I->assertInstanceOf(ProductVariants::class, $variant);
        }
    }
}
