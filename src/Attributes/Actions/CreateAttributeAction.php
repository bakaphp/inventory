<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Attributes\Actions;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Attributes\Models\Attributes;
use Kanvas\Inventory\Enums\State;

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
    public static function execute(UserInterface $user, string $name, ?string $label = null) : Attributes
    {
        return Attributes::findFirstOrCreate([
            'conditions' => 'name = :name: 
                            AND companies_id = :companies_id: 
                            AND is_deleted = 0',
            'bind' => [
                'name' => strtolower($name),
                'companies_id' => $user->currentCompanyId(),
            ]
        ], [
            'name' => strtolower($name),
            'label' => $label ?? $name,
            'apps_id' => App::GLOBAL_APP_ID,
            'companies_id' => $user->currentCompanyId(),
            'users_id' => $user->getId(),
            'is_published' => State::PUBLISHED,

        ]);
    }
}
