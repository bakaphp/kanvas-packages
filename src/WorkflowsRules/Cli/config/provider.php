<?php

/**
 * Enabled providers. Order does matter.
 */

use Kanvas\Packages\Social\Providers\QueueProvider;
use Kanvas\Packages\Social\Providers\RedisProvider;
use  Kanvas\Packages\WorkflowsRules\Providers\DatabaseProvider as WorkflowDatabaseProvider;
use  Kanvas\Packages\WorkflowsRules\Providers\LoggerProvider as WorkflowLoggerProvider;

return [
    QueueProvider::class,
    RedisProvider::class,
    WorkflowDatabaseProvider::class,
    WorkflowLoggerProvider::class
];
