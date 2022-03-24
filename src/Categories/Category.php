<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Categories;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\Database\ModelInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Traits\Searchable;

class Category
{
    use Searchable;

    /**
     * Get model.
     *
     * @return ModelInterface
     */
    public static function getModel() : ModelInterface
    {
        return new Categories();
    }

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
        $category->position = isset($options['position']) && (int) $options['position'] > 0 ? $options['position'] : State::DEFAULT_POSITION;
        $category->is_published = $options['is_published'] ?? State::PUBLISHED;
        $category->is_default = $options['is_default'] ?? State::IS_DEFAULT;
        $category->code = $options['code'] ?? null;
        $category->slug = $options['slug'] ?? '';
        $category->saveOrFail();

        return $category;
    }
}
