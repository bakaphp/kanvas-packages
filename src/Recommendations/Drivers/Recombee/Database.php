<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendations\Drivers\Recombee;

use Baka\Support\Str;
use Kanvas\Packages\Recommendations\Contracts\Database as ContractsDatabase;
use Kanvas\Packages\Recommendations\Contracts\Engine;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Exceptions\ResponseException;
use Recombee\RecommApi\Requests as Reqs;

class Database implements ContractsDatabase
{
    protected string $source;
    protected ?string $itemsType = null;

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
        try {
            $client = $this->execute($engine, $fn);
            $client->send(new Reqs\AddItemProperty('type', 'string'));
        } catch (ResponseException $e) {
            if (Str::contains('already exists', $e->getMessage())) {
                return true;
            }
        }
        return true;
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
        try {
            $client = $this->execute($engine, $fn);
            $client->send(new Reqs\DeleteItemProperty('type', 'string'));
        } catch (ResponseException $e) {
            if (Str::contains('does not exist', $e->getMessage())) {
                return true;
            }
        }

        return true;
    }

    /**
     * Execute the function against recombee.
     *
     * @param Engine $engine
     * @param callable $fn
     *
     * @return bool
     */
    protected function execute(Engine $engine, callable $fn) : Client
    {
        return $fn($engine->connect());
    }

    /**
     * Set the items type.
     *
     * @param string $itemsType
     *
     * @return void
     */
    public function setItemsType(string $itemsType) : void
    {
        $this->itemsType = $itemsType;
    }

    /**
     * Get the items type so we can specify diff items in this db.
     * https://stackoverflow.com/questions/53195137/how-do-i-structure-a-recombee-catalog-with-multiple-item-types.
     *
     * @return string
     */
    public function getItemsType() : string
    {
        return $this->itemsType === null ? $this->getSource() : $this->itemsType;
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
