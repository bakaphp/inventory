<?php

/**
 * Enabled providers. Order does matter.
 */

use Canvas\Providers\AppProvider;
use Canvas\Providers\CacheDataProvider;
use Canvas\Providers\DatabaseProvider as KanvasDatabaseProvider;
use Canvas\Providers\MapperProvider;
use Canvas\Providers\ModelsCacheProvider;
use Canvas\Providers\ModelsMetadataProvider;
use Canvas\Providers\QueueProvider;
use Canvas\Providers\RedisProvider;
use Canvas\Providers\RegistryProvider;
use Canvas\Providers\RequestProvider;
use Canvas\Providers\ResponseProvider;
use Canvas\Providers\UserProvider;
use Canvas\Providers\ViewProvider;
use Kanvas\Inventory\Providers\DatabaseProvider;
use Kanvas\Inventory\Tests\Support\Providers\ConfigProvider;

return [
    KanvasDatabaseProvider::class,
    ModelsMetadataProvider::class,
    RegistryProvider::class,
    AppProvider::class,
    UserProvider::class,
    DatabaseProvider::class,
    RequestProvider::class,
    ResponseProvider::class,
    RedisProvider::class,
    CacheDataProvider::class,
    ModelsCacheProvider::class,
    MapperProvider::class,
    ViewProvider::class,
    ConfigProvider::class,
    QueueProvider::class
];
