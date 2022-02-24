<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Warehouses;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;
use Phalcon\Mvc\Model\ResultsetInterface;

class Warehouse
{
    /**
     * Create new Warehouse.
     *
     * @param UserInterface $user
     * @param string $name
     * @param array $options
     *
     * @return Categories
     */
    public static function create(UserInterface $user, string $name, Regions $region, array $options) : ModelsWarehouse
    {
        $warehouse = new ModelsWarehouse();
        $warehouse->name = $name;
        $warehouse->users_id = $user->getId();
        $warehouse->apps_id = App::GLOBAL_APP_ID;
        $warehouse->companies_id = $user->currentCompanyId();
        $warehouse->regions_id = $region->getId();
        $warehouse->is_default = isset($options['is_default']) ? (int) $options['is_default'] : State::IS_DEFAULT;
        $warehouse->is_published = isset($options['is_default']) ? (int) $options['is_published'] : State::PUBLISHED;
        $warehouse->location = $options['location'] ?? null;
        $warehouse->saveOrFail();

        return $warehouse;
    }

    /**
     * Get the Warehouse by id.
     *
     * @param int $id
     * @param UserInterface $user
     *
     * @return Categories
     */
    public static function getById(int $id, UserInterface $user) : ModelsWarehouse
    {
        return ModelsWarehouse::findFirstOrFail([
            'conditions' => 'id = :id: 
                            AND companies_id = :companies_id:',
            'bind' => [
                'id' => $id,
                'companies_id' => $user->currentCompanyId(),
            ]
        ]);
    }

    /**
     * Get the Warehouse by uuid.
     *
     * @param string $uuid
     * @param UserInterface $user
     *
     * @return Categories
     */
    public static function getByUuid(string $uuid, UserInterface $user) : ModelsWarehouse
    {
        return ModelsWarehouse::findFirstOrFail([
            'conditions' => 'uuid = :uuid: 
                            AND companies_id = :companies_id:',
            'bind' => [
                'uuid' => $uuid,
                'companies_id' => $user->currentCompanyId(),
            ]
        ]);
    }

    /**
     * Get all warehouse associated to a company.
     *
     * @param UserInterface $user
     * @param int $page
     * @param int $limit
     *
     * @return ResultsetInterface
     */
    public static function getAll(UserInterface $user, int $page = 1, int $limit = 25) : ResultsetInterface
    {
        $offset = ($page - 1) * $limit;

        return ModelsWarehouse::find([
            'conditions' => 'companies_id = :company_id: AND is_deleted = 0',
            'bind' => [
                'company_id' => $user->currentCompanyId()
            ],
            'limit' => $limit,
            'offset' => $offset
        ]);
    }
}
