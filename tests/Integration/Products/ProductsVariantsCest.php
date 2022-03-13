<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use IntegrationTester;
use Kanvas\Inventory\Products\Models\Variants as ModelProductVariant;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateProductVariant;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateWarehouse;
use Kanvas\Inventory\Variants\Models\Warehouse;

class ProductsVariantsCest
{
    use CanCreateProductVariant;
    use CanCreateWarehouse;

    public function tesCreate(IntegrationTester $I) : void
    {
        $productVariant = $this->createProductVariant($I);

        $I->assertInstanceOf(ModelProductVariant::class, $productVariant);
    }

    public function testUpdate(IntegrationTester $I) : void
    {
        $productVariant = $this->createProductVariant($I);

        $name = $I->faker()->name;
        $productVariant->name = $name;
        $productVariant->updateOrFail();

        $I->assertEquals($productVariant->name, $name);
    }

    public function addVariantToWarehouse(IntegrationTester $I) : void
    {
        $productVariant = $this->createProductVariant($I);
        $warehouse = $this->createWarehouse($I);

        $productVariantWarehouse = $productVariant->warehouse()->add($warehouse, 1, 1, '33', []);

        $I->assertInstanceOf(Warehouse::class, $productVariantWarehouse);
    }
}
