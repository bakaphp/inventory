<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Categories;

use Baka\Contracts\Database\ModelInterface;
use Kanvas\Inventory\Categories\Models\Categories;
use Kanvas\Inventory\Traits\Searchable;

class CategoryRepository
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
}
