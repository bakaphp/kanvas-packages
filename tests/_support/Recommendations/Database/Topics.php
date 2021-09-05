<?php
declare(strict_types=1);

namespace Kanvas\Packages\Test\Support\Recommendations\Database;

use Kanvas\Packages\Recommendations\Drivers\Recombee\Database as RecombeeDatabase;

class Topics extends RecombeeDatabase
{
    /**
     * Set database source.
     */
    public function __construct()
    {
        $this->setSource('kanvas-dev');
    }
}
