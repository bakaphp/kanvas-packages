<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendations\Drivers\Recombee;

use Kanvas\Packages\Recommendations\Contracts\Database;
use Kanvas\Packages\Recommendations\Contracts\Engine as ContractsEngine;
use Kanvas\Packages\Recommendations\Contracts\Interactions as ContractsInteractions;
use Kanvas\Packages\Recommendations\Contracts\Items as ContractsItems;
use Kanvas\Packages\Recommendations\Contracts\Recommendation as ContractsRecommendations;
use Recombee\RecommApi\Client;

class Engine implements ContractsEngine
{
    protected Database $database;
    protected static array $instances = [];
    protected ?Client $client = null;

    /**
     * Constructor.
     *
     * @param Database $database
     */
    private function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Singleton.
     *
     * @param Database $database
     *
     * @return Engine
     */
    public static function getInstance(Database $database) : Engine
    {
        $source = $database->getSource();
        if (!isset(self::$instances[$source])) {
            self::$instances[$source] = new Engine($database);
        }

        return self::$instances[$source];
    }

    /**
     * Connect to a recommendation DB.
     *
     * @param Database $database
     *
     * @return Client
     */
    public function connect() : Client
    {
        $source = $this->database->getSource();
        if ($this->client === null) {
            $this->client = new Client(
                $source,
                $this->database->getPassword()
            );
        }

        return $this->client;
    }

    /**
     * Database.
     *
     * @return Database
     */
    public function database() : Database
    {
        return $this->database;
    }

    /**
     * Engine Interactions.
     *
     * @return ContractsInteractions
     */
    public function interactions() : ContractsInteractions
    {
        return new Interactions($this);
    }

    /**
     * Get engine items.
     *
     * @return ContractsItems
     */
    public function items() : ContractsItems
    {
        return new Items($this);
    }

    /**
     * Get engine recommendations.
     *
     * @return ContractsRecommendations
     */
    public function recommendation() : ContractsRecommendations
    {
        return new Recommendation($this);
    }
}
