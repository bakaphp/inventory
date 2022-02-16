<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Attributes\Models;

use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\BaseModel;

class Attributes extends BaseModel
{
    public string $uuid;
    public string $name;

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

        $this->setSource('attributes');
    }
}
