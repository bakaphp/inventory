<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Regions\Models;

use Baka\Support\Str;
use Canvas\Models\Behaviors\Uuid;
use Canvas\Models\Currencies;
use Kanvas\Inventory\BaseModel;

class Regions extends BaseModel
{
    public int $apps_id;
    public int $companies_id;
    public string $uuid;
    public string $name;
    public string $slug;
    public string $short_slug;
    public int $currency_id;
    public ?string $settings = null;
    public int $is_default = 0;

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

        $this->setSource('regions');

        $this->belongsTo(
            'currency_id',
            Currencies::class,
            'id',
            [
                'alias' => 'currency',
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
            $this->short_slug = $this->slug;
        }
    }
}
