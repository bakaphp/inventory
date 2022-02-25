<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Attributes;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\Database\ModelInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Attributes\Models\Attributes;
use Kanvas\Inventory\Traits\Searchable;

class Attribute
{
    use Searchable;

    /**
     * Get model.
     *
     * @return ModelInterface
     */
    public static function getModel() : ModelInterface
    {
        return new Attributes();
    }

    /**
     * Create new Attribute.
     *
     * @param UserInterface $user
     * @param string $name
     *
     * @return Attributes
     */
    public static function create(UserInterface $user, string $name) : Attributes
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
