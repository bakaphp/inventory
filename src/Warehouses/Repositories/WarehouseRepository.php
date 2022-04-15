<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Warehouses\Repositories;

use Baka\Contracts\Database\ModelInterface;
use Kanvas\Inventory\Traits\Searchable;
use Kanvas\Inventory\Warehouses\Models\Warehouses as ModelsWarehouse;

class WarehouseRepository
{
    use Searchable;

    /**
     * Get model.
     *
     * @return ModelInterface
     */
    public static function getModel() : ModelInterface
    {
        return new ModelsWarehouse();
    }
}
