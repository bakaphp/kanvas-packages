<?php

namespace Kanvas\Packages\Recommendation\Drivers;

use Kanvas\Packages\Recommendation\Contracts\Database;
use Kanvas\Packages\Recommendation\Contracts\Items as ContractsItems;
use Recombee\RecommApi\Client;

class Items implements ContractsItems
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function add() {}
    public function addMultiple() {}
    public function delete() {}
    public function list() {}

}