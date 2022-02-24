<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Regions;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Enums\App;
use Canvas\Models\Currencies;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Regions\Models\Regions;

class Region
{
    /**
     * Create new Region.
     *
     * @param UserInterface $user
     * @param string $name
     * @param array $options
     *
     * @return Regions
     */
    public static function create(UserInterface $user, string $name, Currencies $currency, array $options) : Regions
    {
        $warehouse = new Regions();
        $warehouse->name = $name;
        $warehouse->users_id = $user->getId();
        $warehouse->apps_id = App::GLOBAL_APP_ID;
        $warehouse->companies_id = $user->currentCompanyId();
        $warehouse->currencies_id = $currency->getId();
        $warehouse->settings = $options['settings'] ?? [];
        $warehouse->is_default = isset($options['is_default']) ? (int) $options['is_default'] : State::IS_DEFAULT;
        $warehouse->is_published = isset($options['is_default']) ? (int) $options['is_published'] : State::PUBLISHED;
        $warehouse->location = $options['location'] ?? null;
        $warehouse->saveOrFail();

        return $warehouse;
    }
}
