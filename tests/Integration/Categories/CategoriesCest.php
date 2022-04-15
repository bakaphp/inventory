<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Categories;

use IntegrationTester;
use Kanvas\Inventory\Categories\Actions\CreateCategoryAction;
use Kanvas\Inventory\Categories\Category;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Categories\Repositories\CategoryRepository;
use Kanvas\Inventory\Enums\State;
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
        $category = CreateCategoryAction::execute(
            $user,
            $I->faker()->name(),
            [
                'position' => 1,
                'isPublished()' => State::PUBLISHED,
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

        $category = CategoryRepository::getById($category->getId(), $user);

        $I->assertInstanceOf(Categories::class, $category);
    }

    public function testGetByUuid(IntegrationTester $I) : void
    {
        $user = new Users();

        $categories = CategoryRepository::getAll($user);

        $category = CategoryRepository::getByUuid($categories->getFirst()->uuid, $user);

        $I->assertInstanceOf(Categories::class, $category);
    }

    public function testGetBySlug(IntegrationTester $I) : void
    {
        $user = new Users();

        $category = CreateCategoryAction::execute(
            $user,
            $I->faker()->name(),
            [
                'position' => 1,
                'isPublished()' => 1,
                'code' => 'test_code',
                'slug' => $I->faker()->slug(),
            ]
        );

        $category = CategoryRepository::getBySlug($category->slug, $user);

        $I->assertInstanceOf(Categories::class, $category);
    }

    public function testGetDefault(IntegrationTester $I) : void
    {
        $user = new Users();

        $default = CategoryRepository::getDefault($user);
        if ($default) {
            $default->delete();
        }

        $category = CreateCategoryAction::execute(
            $user,
            State::DEFAULT_NAME,
            [
                'position' => 1,
                'isPublished' => 1,
                'code' => 'test_code',
                'slug' => State::DEFAULT_NAME_SLUG
            ]
        );

        $category = CategoryRepository::getDefault($user);

        $I->assertInstanceOf(Categories::class, $category);
    }

    public function testGetAll(IntegrationTester $I) : void
    {
        $user = new Users();

        $categories = CategoryRepository::getAll($user);
        $categoriesSecond = CategoryRepository::getAll($user, 1, 1);

        $I->assertTrue($categories->count() > 0);
        $I->assertTrue($categoriesSecond->count() === 1);
    }

    public function testPublish(IntegrationTester $I) : void
    {
        $user = new Users();

        $category = $this->createCategory($I);

        $category->publish();

        $I->assertEquals($category->isPublished(), State::PUBLISHED);
    }

    public function testUnPublish(IntegrationTester $I) : void
    {
        $user = new Users();

        $category = $this->createCategory($I);

        $category->unPublish();

        $I->assertEquals($category->isPublished(), State::UN_PUBLISHED);
    }
}
