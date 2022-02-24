<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Traits;

use Kanvas\Inventory\Enums\State;

trait Publishable
{
    /**
     * Publish the entity.
     *
     * @return void
     */
    public function publish() : void
    {
        $this->is_published = State::PUBLISHED;
        $this->saveOrFail();
    }

    /**
     * Un publish the entity.
     *
     * @return void
     */
    public function unPublish() : void
    {
        $this->is_published = State::UN_PUBLISHED;
        $this->saveOrFail();
    }

    /**
     * Check if the entity is published.
     *
     * @return bool
     */
    public function isPublished() : bool
    {
        return $this->is_published === State::PUBLISHED;
    }
}
