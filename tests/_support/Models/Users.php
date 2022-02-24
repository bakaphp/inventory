<?php

declare(strict_types=1);

namespace Kanvas\Inventory\Tests\Support\Models;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Inventory\BaseModel;

class Users extends BaseModel implements UserInterface
{
    public function getId() : int
    {
        return $this->id ?? 1;
    }

    public function currentCompanyId() : int
    {
        return 1;
    }
}
