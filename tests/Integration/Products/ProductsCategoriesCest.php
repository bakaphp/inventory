<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Products;

use IntegrationTester;
use Kanvas\Inventory\Categories\Category;
use Kanvas\Inventory\Products\Product;
use Kanvas\Inventory\Tests\Support\Models\Users;

class ProductsCategoriesCest extends ProductsCest
{
    public function testAddCategory(IntegrationTester $I)
    {
        $user = new Users();

        $product = Product::getAll($user)->getFirst();
        $newName = $I->faker()->name();

        $category = Category::create($user, $newName, []);

        $productCategory = $product->addCategory($category);

        $I->assertEquals($productCategory->category->name, $newName);
    }

    public function testAddCategories(IntegrationTester $I)
    {
        $user = new Users();

        $product = Product::getAll($user)->getFirst();
        $newName = $I->faker()->name();
        $newNameTwo = $I->faker()->name();

        $category = Category::create($user, $newName, []);
        $categoryTwo = Category::create($user, $newNameTwo, []);

        $productCategory = $product->addCategories([$category, $categoryTwo]);

        $I->assertEquals($productCategory[0]->category->name, $newName);
        $I->assertEquals($productCategory[1]->category->name, $newNameTwo);
    }

    public function testRemoveCategory(IntegrationTester $I)
    {
        $user = new Users();

        $product = Product::getAll($user)->getFirst();
        $newName = $I->faker()->name();

        $category = Category::create($user, $newName, []);

        $productCategory = $product->addCategory($category);


        $I->assertEquals($productCategory->category->name, $newName);
        $I->assertTrue($product->removeCategory($category));
    }
}
