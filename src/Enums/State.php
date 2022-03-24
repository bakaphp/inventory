<?php
declare(strict_types=1);

namespace Kanvas\Inventory\Enums;

class State
{
    public const DEFAULT_PARENT_ID = 0;
    public const DEFAULT_POSITION = 0;
    public const PUBLISHED = 1;
    public const UN_PUBLISHED = 0;
    public const IS_DEFAULT = 0;
    public const DEFAULT = 1;
    public const DEFAULT_NAME = 'Default';
    public const DEFAULT_NAME_SLUG = 'default';
}
