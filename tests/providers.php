<?php

/**
 * Enabled providers. Order does matter.
 */

use Kanvas\Packages\Social\Providers\DatabaseProvider;
use Kanvas\Packages\Social\Providers\QueueProvider;

return [
    DatabaseProvider::class,
    QueueProvider::class
];
