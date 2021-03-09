<?php

/**
 * Enabled providers. Order does matter.
 */

use Kanvas\Packages\Payments\Providers\PlaidProvider;
use Kanvas\Packages\Social\Providers\DatabaseProvider;
use Canvas\Providers\DatabaseProvider as KanvasDatabaseProvider;
use Kanvas\Packages\Social\Providers\QueueProvider;
use Kanvas\Packages\Social\Providers\RedisProvider;
use Kanvas\Packages\WorkflowsRules\Providers\DatabaseProvider as WorkflowDatabaseProvider;

return [
    KanvasDatabaseProvider::class,
    DatabaseProvider::class,
    QueueProvider::class,
    RedisProvider::class,
    PlaidProvider::class,
    WorkflowDatabaseProvider::class
];
