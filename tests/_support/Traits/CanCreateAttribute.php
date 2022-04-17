<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Support\Traits;

use IntegrationTester;
use Kanvas\Inventory\Attributes\Actions\CreateAttributeAction;
use Kanvas\Inventory\Attributes\Models\Attributes;
use Kanvas\Inventory\Categories\Category;
use Kanvas\Inventory\Tests\Support\Models\Users;

trait CanCreateAttribute
{
    /**
     * Create a category.
     *
     * @return Category
     */
    protected function createAttribute(IntegrationTester $I) : Attributes
    {
        $user = new Users();
        $attribute = CreateAttributeAction::execute(
            $user,
            $I->faker()->name(),
        );

        return $attribute;
    }
}
