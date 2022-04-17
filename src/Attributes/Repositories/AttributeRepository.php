<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Attributes\Repositories;

use Baka\Contracts\Database\ModelInterface;
use Kanvas\Inventory\Attributes\Models\Attributes;
use Kanvas\Inventory\Traits\Searchable;

class AttributeRepository
{
    use Searchable;

    /**
     * Get model.
     *
     * @return ModelInterface
     */
    public static function getModel() : ModelInterface
    {
        return new Attributes();
    }
}
