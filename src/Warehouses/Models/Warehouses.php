<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Warehouses\Models;

use Canvas\Models\Behaviors\Uuid;
use Kanvas\Inventory\BaseModel;
use Kanvas\Inventory\Region\Models\Regions;
use Kanvas\Inventory\Traits\Publishable;

class Warehouses extends BaseModel
{
    use Publishable;

    public int $apps_id;
    public int $companies_id;
    public string $uuid;
    public string $name;
    public ?string $location = null;
    public int $is_default = 0;
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

        $this->setSource('warehouses');

        $this->belongsTo(
            'regions_id',
            Regions::class,
            'id',
            [
                'alias' => 'region',
                'reusable' => true
            ]
        );
    }
}
