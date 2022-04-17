<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Traits;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\Database\ModelInterface;
use Kanvas\Inventory\Enums\State;
use Phalcon\Mvc\Model\ResultsetInterface;

trait Searchable
{
    /**
     * set the static model.
     *
     * @return ModelInterface
     */
    abstract public static function getModel() : ModelInterface;

    /**
     * Get all the data from a domain model paginated.
     *
     * @param UserInterface $user
     * @param int $page
     * @param int $limit
     *
     * @return ResultsetInterface
     */
    public static function getAll(UserInterface $user, int $page = 1, int $limit = 10) : ResultsetInterface
    {
        $offset = ($page - 1) * $limit;
        $model = self::getModel();

        $data = $model::find([
            'conditions' => 'companies_id = :company_id: AND is_deleted = 0',
            'bind' => [
                'company_id' => $user->currentCompanyId()
            ],
            'limit' => $limit,
            'offset' => $offset
        ]);

        return $data;
    }

    /**
     * Get a domain data by its id.
     *
     * @param int $id
     * @param UserInterface $user
     *
     * @return ModelInterface
     */
    public static function getById(int $id, UserInterface $user) : ModelInterface
    {
        $model = self::getModel();

        return $model::findFirstOrFail(
            [
                'conditions' => 'id = :id: AND companies_id = :companies_id: AND is_deleted = 0',
                'bind' => [
                    'id' => $id,
                    'companies_id' => $user->currentCompanyId(),
                ]
            ]
        );
    }

    /**
     *  Get a domain data by its uuid.
     *
     * @param string $uui
     * @param UserInterface $user
     *
     * @return ModelInterface
     */
    public static function getByUuid(string $uuid, UserInterface $user) : ModelInterface
    {
        $model = self::getModel();

        return $model::findFirstOrFail([
            'conditions' => 'uuid = :uuid: 
                            AND companies_id = :companies_id: AND is_deleted = 0',
            'bind' => [
                'uuid' => $uuid,
                'companies_id' => $user->currentCompanyId(),
            ]
        ]);
    }

    /**
     * Get the category by id.
     *
     * @param string $slug
     * @param UserInterface $user
     *
     * @return ModelInterface
     */
    public static function getBySlug(string $slug, UserInterface $user) : ModelInterface
    {
        $model = self::getModel();

        return $model::findFirstOrFail([
            'conditions' => 'slug = :slug: 
                            AND companies_id = :companies_id:',
            'bind' => [
                'slug' => $slug,
                'companies_id' => $user->currentCompanyId(),
            ]
        ]);
    }

    /**
     * Get default Entity.
     *
     * @param UserInterface $user
     *
     * @return ModelInterface
     */
    public static function getDefault(UserInterface $user) : ModelInterface
    {
        $model = self::getModel();

        return $model::findFirstOrFail([
            'conditions' => 'is_default = :is_default:
                            AND companies_id = :companies_id:',
            'bind' => [
                'is_default' => State::DEFAULT,
                'companies_id' => $user->currentCompanyId(),
            ]
        ]);
    }
}
