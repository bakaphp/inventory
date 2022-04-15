<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Attributes\Actions;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Attributes\Models\Attributes;

class CreateAttributeAction
{
    /**
     * Create new Attribute.
     *
     * @param UserInterface $user
     * @param string $name
     *
     * @return Attributes
     */
    public static function execute(UserInterface $user, string $name) : Attributes
    {
        $attribute = new Attributes();
        $attribute->name = $name;
        $attribute->apps_id = App::GLOBAL_APP_ID;
        $attribute->companies_id = $user->currentCompanyId();
        $attribute->users_id = $user->getId();
        $attribute->saveOrFail();

        return $attribute;
    }
}
