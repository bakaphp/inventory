<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Products;

use Baka\Contracts\Database\ModelInterface;
use Kanvas\Inventory\Products\Models\Products;
use Kanvas\Inventory\Traits\Searchable;

class ProductRepository
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
}
