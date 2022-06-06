<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products\Actions;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Enums\App;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Enums\State;
use Kanvas\Inventory\Products\Models\Products;

class CreateProductAction
{
    /**
     * Create new Warehouse.
     *
     * @param UserInterface $user
     * @param string $name
     * @param array $options<string, string>
     *
     * @return Products
     */
    public static function execute(
        UserInterface $user,
        string $name,
        Categories $category,
        array $options = []
    ) : Products {
        $product = new Products();
        $product->name = $name;
        $product->users_id = $user->getId();
        $product->apps_id = App::GLOBAL_APP_ID;
        $product->companies_id = $user->currentCompanyId();
        $product->is_published = isset($options['is_published']) ? (int) $options['is_published'] : State::PUBLISHED;
        $product->description = $options['description'] ?? null;
        $product->short_description = $options['short_description'] ?? null;
        $product->slug = $options['slug'] ?? '';
        $product->saveOrFail();

        $category->addProduct($product);

        return $product;
    }
}
