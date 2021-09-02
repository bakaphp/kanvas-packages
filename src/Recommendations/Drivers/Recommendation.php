<?php

namespace Kanvas\Packages\Recommendation\Drivers;

use Kanvas\Packages\Recommendation\Contracts\Database;
use Kanvas\Packages\Recommendation\Contracts\Engine;
use Recombee\RecommApi\Client;

class Recommendation implements Engine
{
    private static array $instances = [];

    public static function connect(Recomend $database)
    {
        $source = $database->getSource();
        if (!isset(self::$instances[$source])) {
            self::$instances[$source] = new Client($source, '--db-private-token--');
        }

        return self::$instances[$source];

    }

}