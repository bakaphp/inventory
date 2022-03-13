<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use IntegrationTester;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateAttribute;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateProductVariant;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateWarehouse;
use Kanvas\Inventory\Variants\Models\ProductVariants as ModelProductVariant;
use Kanvas\Inventory\Variants\Models\ProductVariantWarehouse;

class ProductsVariantsCest
{
    use CanCreateProductVariant;
    use CanCreateWarehouse;
    use CanCreateAttribute;

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

        $I->assertInstanceOf(ProductVariantWarehouse::class, $productVariantWarehouse);
    }

    public function addVariantsAttributes(IntegrationTester $I) : void
    {
        $productVariant = $this->createProductVariant($I);
        $attribute = $this->createAttribute($I);

        $productVariant->attribute()->add($attribute, 'test');

        //we dont need the attribute tue the value of the attribute for this variant
        $I->assertEquals($productVariant->getAttributes()->count(), 1);
    }
}
