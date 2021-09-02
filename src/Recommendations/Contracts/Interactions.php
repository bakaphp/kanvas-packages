<?php

namespace Kanvas\Packages\Recommendation\Contracts;

use Phalcon\Mvc\ModelInterface;


interface Interactions
{
    public function like(ModelInterface $model);
    public function view(ModelInterface $model);
    public function purchase(ModelInterface $model);
    public function bookmark(ModelInterface $model);
}