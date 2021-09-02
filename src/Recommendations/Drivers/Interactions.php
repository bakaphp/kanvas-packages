<?php

namespace Kanvas\Packages\Recommendation\Drivers;

use Kanvas\Packages\Recommendation\Contracts\Database;
use Kanvas\Packages\Recommendation\Contracts\Interactions as ContractsInteractions;
use Phalcon\Mvc\ModelInterface;
use Recombee\RecommApi\Client;

class Interactions implements ContractsInteractions
{
    protected Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function like(ModelInterface $model) {}
    public function view(ModelInterface $model) {}
    public function purchase(ModelInterface $model) {}
    public function bookmark(ModelInterface $model) {}
}