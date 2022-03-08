<?php

declare(strict_types=1);

namespace Kanvas\Guild\Tests\Integration\Products;

use IntegrationTester;
use Kanvas\Inventory\Categories\Category;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Products\Product;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Tests\Support\Models\Users;

class ProductsCest
{
    /**
     * Create a category.
     *
     * @return Products
     */
    protected function createProduct(IntegrationTester $I) : Products
    {
        $user = new Users();
        $region = Regions::findFirst();

        $product = Product::create(
            $user,
            $I->faker()->name(),
            Categories::findFirstOrFail(),
            [
                'is_published' => State::PUBLISHED,
                'description' => $I->faker()->text(),
                'short_description' => $I->faker()->text(),
            ]
        );

        return $product;
    }

    public function tesCreate(IntegrationTester $I) : void
    {
        $product = $this->createProduct($I);

        $I->assertInstanceOf(Products::class, $product);
    }

    public function testUpdateProduct(IntegrationTester $I) : void
    {
        $user = new Users();

        $product = $this->createProduct($I);

        $newName = $I->faker()->name();
        $product->name = $newName;
        $product->saveOrFail();

        $I->assertEquals($product->name, $newName);
    }

    public function testGetById(IntegrationTester $I) : void
    {
        $user = new Users();

        $product = $this->createProduct($I);

        $product = Product::getById($product->getId(), $user);

        $I->assertInstanceOf(Products::class, $product);
    }

    public function testGetByUuid(IntegrationTester $I) : void
    {
        $user = new Users();

        $product = Product::getAll($user);

        $product = Product::getByUuid($product->getFirst()->uuid, $user);

        $I->assertInstanceOf(Products::class, $product);
    }

    public function testGetAll(IntegrationTester $I) : void
    {
        $user = new Users();

        $product = Product::getAll($user);
        $productSecond = Product::getAll($user, 1, 1);

        $I->assertTrue($product->count() > 0);
        $I->assertTrue($productSecond->count() === 1);
    }

    public function testPublish(IntegrationTester $I) : void
    {
        $user = new Users();

        $product = $this->createProduct($I);

        $product->publish();

        $I->assertEquals($product->isPublished(), State::PUBLISHED);
    }

    public function testUnPublish(IntegrationTester $I) : void
    {
        $user = new Users();

        $product = $this->createProduct($I);

        $product->unPublish();

        $I->assertEquals($product->isPublished(), State::UN_PUBLISHED);
    }

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
