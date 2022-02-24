<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Categories;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Categories\Enums\State;
use Kanvas\Inventory\Categories\Models\Categories;
use Phalcon\Mvc\Model\ResultsetInterface;

class Category
{
    /**
     * Create new Category.
     *
     * @param UserInterface $user
     * @param string $name
     * @param array $options
     *
     * @return Categories
     */
    public static function create(UserInterface $user, string $name, array $options) : Categories
    {
        $category = new Categories();
        $category->name = $name;
        $category->users_id = $user->getId();
        $category->apps_id = App::GLOBAL_APP_ID;
        $category->companies_id = $user->currentCompanyId();
        $category->parent_id = State::DEFAULT_PARENT_ID;
        $category->position = (int) $options['position'] ?? State::DEFAULT_POSITION;
        $category->is_published = $options['is_published'] ?? State::PUBLISHED;
        $category->code = $options['code'] ?? null;
        $category->slug = $options['slug'] ?? '';
        $category->saveOrFail();

        return $category;
    }

    /**
     * Get the category by id.
     *
     * @param int $id
     * @param UserInterface $user
     *
     * @return Categories
     */
    public static function getById(int $id, UserInterface $user) : Categories
    {
        return Categories::findFirstOrFail([
            'conditions' => 'id = :id: 
                            AND companies_id = :companies_id:',
            'bind' => [
                'id' => $id,
                'companies_id' => $user->currentCompanyId(),
            ]
        ]);
    }

    /**
     * Get the category by id.
     *
     * @param string $uui
     * @param UserInterface $user
     *
     * @return Categories
     */
    public static function getByUuid(string $uuid, UserInterface $user) : Categories
    {
        return Categories::findFirstOrFail([
            'conditions' => 'uuid = :uuid: 
                            AND companies_id = :companies_id:',
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
     * @return Categories
     */
    public static function getBySlug(string $slug, UserInterface $user) : Categories
    {
        return Categories::findFirstOrFail([
            'conditions' => 'slug = :slug: 
                            AND companies_id = :companies_id:',
            'bind' => [
                'slug' => $slug,
                'companies_id' => $user->currentCompanyId(),
            ]
        ]);
    }

    /**
     * Get all pipelines associated to a company.
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

        return Categories::find([
            'conditions' => 'companies_id = :company_id: AND is_deleted = 0',
            'bind' => [
                'company_id' => $user->currentCompanyId()
            ],
            'limit' => $limit,
            'offset' => $offset
        ]);
    }
}
