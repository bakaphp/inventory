<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Channels;

use Baka\Contracts\Database\ModelInterface;
use Kanvas\Inventory\Channels\Models\Channels as ModelsChannels;
use Kanvas\Inventory\Traits\Searchable;

class ChannelRepository
{
    use Searchable;

    /**
     * Get model.
     *
     * @return ModelInterface
     */
    public static function getModel() : ModelInterface
    {
        return new ModelsChannels();
    }
}
