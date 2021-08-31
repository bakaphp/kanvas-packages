<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation\Drivers\Recombee;

use Kanvas\Packages\Recommendation\Contracts\Database;
use Kanvas\Packages\Recommendation\Contracts\Engine as ContractsEngine;
use Kanvas\Packages\Recommendation\Contracts\Interactions as ContractsInteractions;
use Kanvas\Packages\Recommendation\Contracts\Items as ContractsItems;
use Kanvas\Packages\Recommendation\Contracts\Recommendation as ContractsRecommendations;
use Recombee\RecommApi\Client;

class Engine implements ContractsEngine
{
    private static array $instances = [];

    /**
     * Connect to a recommendation DB.
     *
     * @param Database $database
     *
     * @return mixed
     */
    public static function connect(Database $database) : Client
    {
        $source = $database->getSource();
        if (!isset(self::$instances[$source])) {
            self::$instances[$source] = new Client(
                $source,
                $database->getPassword()
            );
        }

        return self::$instances[$source];
    }

    /**
     * Engine Interactions.
     *
     * @return ContractsInteractions
     */
    public function interactions() : ContractsInteractions
    {
        return new Interactions(new self());
    }

    /**
     * Get engine items.
     *
     * @return ContractsItems
     */
    public function items() : ContractsItems
    {
        return new Items(new self());
    }

    /**
     * Get engine recommendations.
     *
     * @return ContractsRecommendations
     */
    public function recommendation() : ContractsRecommendations
    {
        return new Recommendation(new self());
    }
}
