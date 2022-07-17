<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Attributes\Models;

use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Traits\Publishable;

class Attributes extends BaseModel
{
    use Publishable;

    public string $uuid;
    public string $name;
    public ?string $label = null;
    public int $is_published = 1;

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
