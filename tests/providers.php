<?php

/**
 * Enabled providers. Order does matter.
 */

use Kanvas\Packages\Social\Providers\EventsManagerProvider;
use Kanvas\Packages\Social\Providers\DatabaseProvider;
use Kanvas\Packages\Social\Providers\RedisProvider;

return [
    DatabaseProvider::class,
    EventsManagerProvider::class,
    RedisProvider::class
];
