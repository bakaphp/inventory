<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Traits;

use Kanvas\Inventory\Enums\State;

trait Publishable
{
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
