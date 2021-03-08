<?php

/**
 * Enabled providers. Order does matter.
 */

use Kanvas\Packages\Payments\Providers\PlaidProvider;
use Kanvas\Packages\Social\Providers\DatabaseProvider;
use Kanvas\Packages\Social\Providers\QueueProvider;
use Kanvas\Packages\Social\Providers\RedisProvider;
use Kanvas\Packages\WorkflowsRules\Providers\DatabaseProvider as WorkflowDatabaseProvider;
use Kanvas\Packages\Social\Providers\ModelsCacheProvider;
use Kanvas\Packages\Social\Providers\ConfigProvider;
use Canvas\Providers\AppProvider;

return [
    ConfigProvider::class,
    AppProvider::class,
    DatabaseProvider::class,
    QueueProvider::class,
    RedisProvider::class,
    ModelsCacheProvider::class,
    PlaidProvider::class,
    WorkflowDatabaseProvider::class
];
