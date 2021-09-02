<?php

namespace Kanvas\Packages\Recommendation;

use Kanvas\Packages\Recommendation\Contracts\Recomendation;
use Kanvas\Packages\Recommendation\Contracts\Items as ContractsItems;
use Recombee\RecommApi\Client;

class Items implements ContractsItems
{
    protected Recomendation $recomendation;

    public function __construct(Recomendation $recomendation)
    {
        $this->recomendation = $recomendation;
    }

    public function add() {}
    public function addMultiple() {}
    public function delete() {}
    public function list() {}

}