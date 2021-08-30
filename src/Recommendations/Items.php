<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation;

use Kanvas\Packages\Recommendation\Contracts\Items as ContractsItems;

class Items implements ContractsItems
{
    protected Recommendation $recomendation;

    public function __construct(Recommendation $recomendation)
    {
        $this->recomendation = $recomendation;
    }

    public function add()
    {
    }
    public function addMultiple()
    {
    }
    public function delete()
    {
    }
    public function list()
    {
    }
}
