<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Contracts;

use Generator;

interface ExportableInterface
{
    /**
     * Get all entities of the given type of records we want to process.
     *
     * @return Generator
     */
    public function getAllEntities() : Generator;
}
