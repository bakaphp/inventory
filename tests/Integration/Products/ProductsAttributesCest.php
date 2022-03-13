<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use IntegrationTester;
use Kanvas\Inventory\Attributes\Attribute;
use Kanvas\Inventory\Tests\Support\Models\Users;

class ProductsAttributesCest extends ProductsCest
{
    public function testAddAttribute(IntegrationTester $I)
    {
        $user = new Users();

        $newName = $I->faker()->name();
        $value = $I->faker()->name();
        $product = $this->createProduct($I);
        $attribute = Attribute::create($user, $newName, []);

        $productAttribute = $product->addAttribute($attribute, $value);


        $I->assertEquals($productAttribute->value, $value);
    }
}
