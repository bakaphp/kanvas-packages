<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation\Drivers\Recombee;

use Kanvas\Packages\Recommendation\Contracts\Database as ContractsDatabase;
use Kanvas\Packages\Recommendation\Contracts\Engine;
use Recombee\RecommApi\Requests as Reqs;

class Database implements ContractsDatabase
{
    protected string $source;

    /**
     * Create a database.
     *
     * @param Engine $engine
     * @param callable $fn
     *
     * @return bool
     */
    public function create(Engine $engine, callable $fn) : bool
    {
        $engine::connect()->send(
            new Reqs\ResetDatabase()
        ); // Clear everything from the database

        $fn($engine::connect());

        return true;
    }

    /**
     * clean up the db.
     *
     * @param Engine $engine
     *
     * @return bool
     */
    public function delete(Engine $engine) : bool
    {
        $engine::connect()->send(
            new Reqs\ResetDatabase()
        ); // Clear everything from the database

        return true;
    }

    /**
     * Set source.
     *
     * @param string $source
     *
     * @return void
     */
    public function setSource(string $source) : void
    {
        $this->source = $source;
    }

    /**
     * Get database name.
     *
     * @return string
     */
    public function getSource() : string
    {
        return $this->source;
    }

    /**
     * Get database password.
     *
     * @return string
     */
    public function getPassword() : string
    {
        return getenv('RECOMBEE_PRIVATE_TOKEN');
    }
}
