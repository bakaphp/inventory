<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Regions;

use Baka\Contracts\Database\ModelInterface;
use Kanvas\Inventory\Regions\Models\Regions;
use Kanvas\Inventory\Traits\Searchable;

class RegionRepository
{
    use Searchable;

    /**
     * Get model.
     *
     * @return ModelInterface
     */
    public static function getModel() : ModelInterface
    {
        return new Regions();
    }
}
