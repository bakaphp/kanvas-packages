<?php

declare(strict_types=1);
/**
 * Enabled providers. Order does matter.
 */

use Kanvas\Packages\Social\Providers\QueueProvider;
use Kanvas\Packages\Social\Providers\RedisProvider;
use  Kanvas\Packages\WorkflowsRules\Providers\DatabaseProvider as WorkflowDatabaseProvider;
use  Kanvas\Packages\WorkflowsRules\Providers\LoggerProvider as WorkflowLoggerProvider;
use  Kanvas\Packages\WorkflowsRules\Providers\MailProvider as WorkflowMailProvider;
use  Kanvas\Packages\WorkflowsRules\Providers\TemplateProvider as WorkflowTemplateProvider;

return [
    QueueProvider::class,
    RedisProvider::class,
    WorkflowDatabaseProvider::class,
    WorkflowLoggerProvider::class,
    WorkflowMailProvider::class,
    WorkflowTemplateProvider::class
];
