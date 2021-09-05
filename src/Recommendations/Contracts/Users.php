<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendations\Contracts;

use Baka\Contracts\Auth\UserInterface;

interface Users
{
    public function __construct(Engine $engine);
    public function add(UserInterface $model) : bool;
    public function delete(UserInterface $model) : bool;
}
