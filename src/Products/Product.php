<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\Database\ModelInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Traits\Searchable;

class Product
{
    use Searchable;

    /**
     * Get model.
     *
     * @return ModelInterface
     */
    public static function getModel() : ModelInterface
    {
        return new Products();
    }

    /**
     * Create new Warehouse.
     *
     * @param UserInterface $user
     * @param string $name
     * @param array $options
     *
     * @return Products
     */
    public static function create(
        UserInterface $user,
        string $name,
        Categories $category,
        array $options
    ) : Products {
        $product = new Products();
        $product->name = $name;
        $product->users_id = $user->getId();
        $product->apps_id = App::GLOBAL_APP_ID;
        $product->companies_id = $user->currentCompanyId();
        $product->is_published = isset($options['is_published']) ? (int) $options['is_published'] : State::PUBLISHED;
        $product->saveOrFail();

        $category->addProduct($product);

        return $product;
    }
}
