<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Categories\Models;

use Baka\Support\Str;
use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Categories\Enums\State;

class Categories extends BaseModel
{
    public int $apps_id;
    public int $companies_id;
    public string $uuid;
    public string $name;
    public string $slug;
    public ?string $code = null;
    public int $position = 0;
    public int $parent_id = 0;
    public int $is_published = 0;

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

        $this->setSource('categories');

        $this->belongsTo(
            'parent_id',
            self::class,
            'id',
            [
                'alias' => 'parent',
                'reusable' => true
            ]
        );
    }

    /**
     * Before create.
     *
     * @return void
     */
    public function beforeCreate()
    {
        parent::beforeCreate();

        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }
    }

    /**
     * Set category parent.
     *
     * @param Categories $parent
     *
     * @return void
     */
    public function setParent(Categories $parent) : void
    {
        $this->parent_id = $parent->getId();
        $this->saveOrFail();
    }

    /**
     * Add a child to this category.
     *
     * @param Categories $child
     *
     * @return void
     */
    public function addChild(Categories $child) : void
    {
        $child->parent_id = $this->getId();
        $child->saveOrFail();
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
