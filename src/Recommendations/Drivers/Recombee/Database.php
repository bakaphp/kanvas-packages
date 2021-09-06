<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendations\Drivers\Recombee;

use Kanvas\Packages\Recommendations\Contracts\Database as ContractsDatabase;
use Kanvas\Packages\Recommendations\Contracts\Engine;

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
        //same function
        return $this->execute($engine, $fn);
    }

    /**
     * clean up the db.
     *
     * @param Engine $engine
     *
     * @return bool
     */
    public function delete(Engine $engine, callable $fn) : bool
    {
        //same function
        return $this->execute($engine, $fn);
    }

    /**
     * Execute the function against recombee.
     *
     * @param Engine $engine
     * @param callable $fn
     *
     * @return bool
     */
    protected function execute(Engine $engine, callable $fn) : bool
    {
        $fn($engine->connect());

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
