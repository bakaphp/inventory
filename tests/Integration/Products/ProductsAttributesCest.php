<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use IntegrationTester;
use Kanvas\Inventory\Attributes\Attribute;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateProducts;

class ProductsAttributesCest
{
    use CanCreateProducts;

    public function testAddAttribute(IntegrationTester $I)
    {
        $user = new Users();

        $newName = $I->faker()->name();
        $value = $I->faker()->name();
        $product = $this->createProduct($I);
        $attribute = Attribute::create($user, $newName, []);

        $productAttribute = $product->attributes()->add($attribute, $value);

        $I->assertEquals($productAttribute->value, $value);
    }

    public function testAddAttributes(IntegrationTester $I)
    {
        $user = new Users();

        $product = $this->createProduct($I);
        $newName = $I->faker()->name();
        $newNameTwo = $I->faker()->name();

        $attribute = Attribute::create($user, $newName, []);
        $attributeTwo = Attribute::create($user, $newNameTwo, []);

        $productAttribute = $product->attributes()->addMultiple(
            [
                ['attribute' => $attribute, 'value' => 'value'],
                ['attribute' => $attributeTwo, 'value' => 'value'],
            ]
        );

        $I->assertEquals($productAttribute[0]->attribute->name, $newName);
        $I->assertEquals($productAttribute[1]->attribute->name, $newNameTwo);
    }

    public function testUpdateAttribute(IntegrationTester $I)
    {
        $user = new Users();

        $newName = $I->faker()->name();
        $value = $I->faker()->name();
        $newValue = $I->faker()->name();
        $product = $this->createProduct($I);
        $attribute = Attribute::create($user, $newName, []);

        $productAttribute = $product->attributes()->add($attribute, $value);
        $productAttributeUpdate = $product->attributes()->update($attribute, $newValue);

        $I->assertEquals($productAttribute->value, $value);
        $I->assertEquals($productAttributeUpdate->value, $newValue);
    }

    public function testRemoveCategory(IntegrationTester $I)
    {
        $user = new Users();

        $newName = $I->faker()->name();
        $value = $I->faker()->name();
        $product = $this->createProduct($I);
        $attribute = Attribute::create($user, $newName, []);

        $product->attributes()->add($attribute, $value);

        $I->assertTrue($product->attributes()->delete($attribute));
    }
}
