<?php

namespace Kanvas\Packages\Recommendation\Contracts;

use Baka\Contracts\Auth\UserInterface;


interface Users
{
    public function add(UserInterface $model);
    public function delete(UserInterface $model);
}