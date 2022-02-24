<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Attributes\Models;

use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Enums\State;

class Attributes extends BaseModel
{
    public string $uuid;
    public string $name;
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

    /**
     * Publish the category.
     *
     * @return void
     */
    public function publish() : void
    {
        $this->is_published = State::PUBLISHED;
        $this->saveOrFail();
    }

    /**
     * Un publish the category.
     *
     * @return void
     */
    public function unPublish() : void
    {
        $this->is_published = State::UN_PUBLISHED;
        $this->saveOrFail();
    }
}
