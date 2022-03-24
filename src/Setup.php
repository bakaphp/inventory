<?php
declare(strict_types=1);

namespace Kanvas\Inventory;

use Canvas\Models\Companies;

class Setup
{
    protected Companies $company;

    public function __construct(Companies $company)
    {
        $this->company = $company;
    }

    public function run() : void
    {
    }
}
