<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation\Contracts;

use Baka\Contracts\ModelInterface;

interface Interactions
{
    public function like(ModelInterface $model) : bool;
    public function view(ModelInterface $model) : bool;
    public function purchase(ModelInterface $model) : bool;
    public function bookmark(ModelInterface $model) : bool;
}
