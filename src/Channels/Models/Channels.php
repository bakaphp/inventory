<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Channels\Models;

use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Enums\State;

class Channels extends BaseModel
{
    public string $uuid;
    public string $name;
    public string $slug;
    public ?string $description = null;
    public string $user_id;
    public int $is_default = State::IS_DEFAULT;
    public int $companies_id;
    public int $apps_id;
    public int $is_published = State::PUBLISHED;

    /**
     * Initialize.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->addBehavior(
            new Uuid()
        );

        $this->setSource('channels');
    }
}
