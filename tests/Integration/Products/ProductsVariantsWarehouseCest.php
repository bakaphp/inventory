<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use IntegrationTester;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateAttribute;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateProductVariant;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateWarehouse;
use Kanvas\Inventory\Variants\Models\ProductVariantWarehouse;
use Kanvas\Inventory\Variants\ProductVariantWarehouse as VariantsProductVariantWarehouse;
use Phalcon\Utils\Slug;

class ProductsVariantsCest
{
    use CanCreateProductVariant;
    use CanCreateWarehouse;
    use CanCreateAttribute;

    public function testGetAll(IntegrationTester $I) : void
    {
        $productVariant = $this->createProductVariant($I);
        $warehouse = $this->createWarehouse($I);

        $productVariantWarehouse = $productVariant->warehouse()->add(
            $warehouse,
            $I->faker()->randomNumber(1),
            $I->faker()->randomNumber(2),
            Slug::generate($I->faker()->name),
            []
        );

        $I->assertInstanceOf(ProductVariantWarehouse::class, $productVariantWarehouse);

        $user = new Users();
        $productVariantsWarehouse = VariantsProductVariantWarehouse::getAll($user, $warehouse);

        $I->assertEquals(ProductVariantWarehouse::class, get_class($productVariantsWarehouse->getFirst()));
    }

    public function testGetByUuid(IntegrationTester $I) : void
    {
        $productVariant = $this->createProductVariant($I);
        $warehouse = $this->createWarehouse($I);

        $productVariantWarehouse = $productVariant->warehouse()->add(
            $warehouse,
            $I->faker()->randomNumber(1),
            $I->faker()->randomNumber(2),
            Slug::generate($I->faker()->name),
            []
        );

        $I->assertInstanceOf(ProductVariantWarehouse::class, $productVariantWarehouse);

        $user = new Users();
        $productVariantsWarehouse = VariantsProductVariantWarehouse::getByUuid($productVariant->uuid, $user, $warehouse);
        $I->assertEquals(ProductVariantWarehouse::class, get_class($productVariantsWarehouse));
    }

    public function testGetByProduct(IntegrationTester $I) : void
    {
        $productVariant = $this->createProductVariant($I);
        $warehouse = $this->createWarehouse($I);

        $productVariantWarehouse = $productVariant->warehouse()->add(
            $warehouse,
            $I->faker()->randomNumber(1),
            $I->faker()->randomNumber(2),
            Slug::generate($I->faker()->name),
            []
        );

        $I->assertInstanceOf(ProductVariantWarehouse::class, $productVariantWarehouse);

        $user = new Users();
        $productVariantsWarehouse = VariantsProductVariantWarehouse::getAllByProduct($user, $warehouse, $productVariant->getProduct());
        $I->assertEquals(ProductVariantWarehouse::class, get_class($productVariantsWarehouse->getFirst()));
    }
}
