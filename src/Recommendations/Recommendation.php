<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation;

use Exception;
use Kanvas\Packages\Recommendation\Contracts\Database;
use Kanvas\Packages\Recommendation\Contracts\Engine;
use Kanvas\Packages\Recommendation\Contracts\Interactions;
use Kanvas\Packages\Recommendation\Contracts\Items;
use Kanvas\Packages\Recommendation\Contracts\Recommendation as ContractsRecommendation;
use Phalcon\Di\Injectable;

class Recommendation extends Injectable
{
    protected Engine $client;
    protected Database $database;

    /**
     * Initialize the engine connection to the specify db.
     *
     * @param Database $database
     * @param Engine|null $engine
     */
    public function __construct(Database $database, ?Engine $engine = null)
    {
        $this->database = $database;

        if ($engine === null) {
            $engine = $this->getDI->get('recommendation');

            if (!$engine instanceof Engine) {
                throw new Exception('Not a recommendation driver');
            }
        }

        $this->client = $engine::connect($this->database);
    }

    /**
     * Get the recommendation client.
     *
     * @return Engine
     */
    public function getClient() : Engine
    {
        return $this->client;
    }

    /**
     * Get interactions for this recommendation engine.
     *
     * @return Interactions
     */
    public function interactions() : Interactions
    {
        return $this->client->interactions();
    }

    /**
     * Get items from this recommendation engine.
     *
     * @return Items
     */
    public function items() : Items
    {
        return $this->client->items();
    }

    /**
     * Create a new database.
     *
     * @param callable $fn
     *
     * @return bool
     */
    public function create(callable $fn) : bool
    {
        return $this->database->create($this->client, $fn);
    }

    /**
     * Delete a database.
     *
     * @return bool
     */
    public function delete() : bool
    {
        return $this->database->delete($this->client);
    }

    /**
     * Get the recommendation entity.
     *
     * @return void
     */
    public function get() : ContractsRecommendation
    {
        return $this->client->recommendation();
    }
}
