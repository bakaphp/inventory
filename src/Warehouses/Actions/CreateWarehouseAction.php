<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Warehouses\Actions;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;

class CreateWarehouseAction
{
    /**
     * Create new Warehouse.
     *
     * @param UserInterface $user
     * @param string $name
     * @param array $options
     *
     * @return ModelsWarehouse
     */
    public static function execute(UserInterface $user, string $name, Regions $region, array $options) : ModelsWarehouse
    {
        $warehouse = new ModelsWarehouse();
        $warehouse->name = $name;
        $warehouse->users_id = $user->getId();
        $warehouse->apps_id = App::GLOBAL_APP_ID;
        $warehouse->companies_id = $user->currentCompanyId();
        $warehouse->regions_id = $region->getId();
        $warehouse->is_default = isset($options['is_default']) ? (int) $options['is_default'] : State::IS_DEFAULT;
        $warehouse->is_published = isset($options['is_published']) ? (int) $options['is_published'] : State::PUBLISHED;
        $warehouse->location = $options['location'] ?? null;
        $warehouse->saveOrFail();

        return $warehouse;
    }
}
