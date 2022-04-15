<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Integration\Attributes;

use IntegrationTester;
use Kanvas\Inventory\Attributes\AttributeRepository;
use Kanvas\Inventory\Attributes\Models\Attributes;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Tests\Support\Models\Users;
use Kanvas\Inventory\Tests\Support\Traits\CanCreateAttribute;

class AttributesCest
{
    use CanCreateAttribute;

    public function testCreate(IntegrationTester $I) : void
    {
        $user = new Users();

        $attribute = $this->createAttribute($I);

        $I->assertInstanceOf(Attributes::class, $attribute);
    }

    public function testUpdate(IntegrationTester $I) : void
    {
        $user = new Users();

        $attribute = $this->createAttribute($I);

        $newName = $I->faker()->name();
        $attribute->name = $newName;
        $attribute->saveOrFail();

        $I->assertEquals($attribute->name, $newName);
    }

    public function testGetById(IntegrationTester $I) : void
    {
        $user = new Users();

        $attribute = $this->createAttribute($I);

        $attribute = AttributeRepository::getById($attribute->getId(), $user);

        $I->assertInstanceOf(Attributes::class, $attribute);
    }

    public function testGetByUuid(IntegrationTester $I) : void
    {
        $user = new Users();

        $categories = AttributeRepository::getAll($user);

        $attribute = AttributeRepository::getByUuid($categories->getFirst()->uuid, $user);

        $I->assertInstanceOf(Attributes::class, $attribute);
    }



    public function testGetAll(IntegrationTester $I) : void
    {
        $user = new Users();

        $categories = AttributeRepository::getAll($user);
        $categoriesSecond = AttributeRepository::getAll($user, 1, 1);

        $I->assertTrue($categories->count() > 0);
        $I->assertTrue($categoriesSecond->count() === 1);
    }

    public function testPublish(IntegrationTester $I) : void
    {
        $user = new Users();

        $attribute = $this->createAttribute($I);

        $attribute->publish();

        $I->assertEquals($attribute->isPublished(), State::PUBLISHED);
    }

    public function testUnPublish(IntegrationTester $I) : void
    {
        $user = new Users();

        $attribute = $this->createAttribute($I);

        $attribute->unPublish();

        $I->assertEquals($attribute->isPublished(), State::UN_PUBLISHED);
    }
}
