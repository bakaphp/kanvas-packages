<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation\Contracts;

use Baka\Contracts\Auth\UserInterface;

interface Users
{
    public function add(UserInterface $model) : bool;
    public function delete(UserInterface $model) : bool;
}
