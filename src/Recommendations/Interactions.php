<?php

namespace Kanvas\Packages\Recommendation;

use Kanvas\Packages\Recommendation\Contracts\Interactions as ContractsInteractions;
use Phalcon\Mvc\ModelInterface;

class Interactions implements ContractsInteractions
{
    protected Recomendation $recomendation;

    public function __construct(Recomendation $recommendation)
    {
        $this->recommendation = $recommendation;
    }

    public function like(ModelInterface $model) {}
    public function view(ModelInterface $model) {}
    public function purchase(ModelInterface $model) {}
    public function bookmark(ModelInterface $model) {}
}