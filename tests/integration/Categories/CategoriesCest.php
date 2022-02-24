<?php

declare(strict_types=1);

namespace Kanvas\Guild\Tests\Integration\Categories;

use IntegrationTester;
use Kanvas\Inventory\Categories\Category;
use Kanvas\Inventory\Categories\Enums\State;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Tests\Support\Models\Users;

class CategoriesCest
{
    /**
     * Create a category.
     *
     * @return Category
     */
    protected function createCategory(IntegrationTester $I) : Categories
    {
        $user = new Users();
        $category = Category::create(
            $user,
            $I->faker()->name(),
            [
                'position' => 1,
                'is_published' => State::PUBLISHED,
                'code' => 'TEST_CODE',
            ]
        );

        return $category;
    }

    public function tesCreate(IntegrationTester $I) : void
    {
        $user = new Users();

        $category = $this->createCategory($I);

        $I->assertInstanceOf(Categories::class, $category);
    }

    public function testUpdateCategory(IntegrationTester $I) : void
    {
        $user = new Users();

        $category = $this->createCategory($I);

        $newName = $I->faker()->name();
        $category->name = $newName;
        $category->saveOrFail();

        $I->assertEquals($category->name, $newName);
    }

    public function testGetById(IntegrationTester $I) : void
    {
        $user = new Users();

        $category = $this->createCategory($I);

        $category = Category::getById($category->getId(), $user);

        $I->assertInstanceOf(Categories::class, $category);
    }

    public function testGetByUuid(IntegrationTester $I) : void
    {
        $user = new Users();

        $categories = Category::getAll($user);

        $category = Category::getByUuid($categories->getFirst()->uuid, $user);

        $I->assertInstanceOf(Categories::class, $category);
    }

    public function testGetBySlug(IntegrationTester $I) : void
    {
        $user = new Users();

        $category = Category::create(
            $user,
            $I->faker()->name(),
            [
                'position' => 1,
                'is_published' => 1,
                'code' => 'test_code',
                'slug' => $I->faker()->slug(),
            ]
        );

        $category = Category::getBySlug($category->slug, $user);

        $I->assertInstanceOf(Categories::class, $category);
    }

    public function testGetAll(IntegrationTester $I) : void
    {
        $user = new Users();

        $categories = Category::getAll($user);
        $categoriesSecond = Category::getAll($user, 1, 1);

        $I->assertTrue($categories->count() > 0);
        $I->assertTrue($categoriesSecond->count() === 1);
    }

    public function testPublish(IntegrationTester $I) : void
    {
        $user = new Users();

        $category = $this->createCategory($I);

        $category->publish();

        $I->assertEquals($category->is_published, State::PUBLISHED);
    }

    public function testUnPublish(IntegrationTester $I) : void
    {
        $user = new Users();

        $category = $this->createCategory($I);

        $category->unPublish();

        $I->assertEquals($category->is_published, State::UN_PUBLISHED);
    }
}
