<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Attributes;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Attributes\Models\Attributes;
use Phalcon\Mvc\Model\ResultsetInterface;

class Attribute
{
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

    /**
     * Get the Attributes by id.
     *
     * @param int $id
     * @param UserInterface $user
     *
     * @return Attributes
     */
    public static function getById(int $id, UserInterface $user) : Attributes
    {
        return Attributes::findFirstOrFail([
            'conditions' => 'id = :id: 
                            AND companies_id = :companies_id:',
            'bind' => [
                'id' => $id,
                'companies_id' => $user->currentCompanyId(),
            ]
        ]);
    }

    /**
     * Get the Attributes by uuid.
     *
     * @param string $uuid
     * @param UserInterface $user
     *
     * @return Attributes
     */
    public static function getByUuid(string $uuid, UserInterface $user) : Attributes
    {
        return Attributes::findFirstOrFail([
            'conditions' => 'uuid = :uuid: 
                            AND companies_id = :companies_id:',
            'bind' => [
                'uuid' => $uuid,
                'companies_id' => $user->currentCompanyId(),
            ]
        ]);
    }

    /**
     * Get all pipelines associated to a company.
     *
     * @param int $page
     * @param int $limit
     *
     * @return ResultsetInterface
     */
    public static function getAll(UserInterface $user, int $page = 1, int $limit = 25) : ResultsetInterface
    {
        $offset = ($page - 1) * $limit;

        return Attributes::find([
            'conditions' => 'companies_id = :company_id: AND is_deleted = 0',
            'bind' => [
                'company_id' => $user->currentCompanyId()
            ],
            'limit' => $limit,
            'offset' => $offset
        ]);
    }
}
