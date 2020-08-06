<?php

namespace Kanvas\Packages\Test\Support\Models;

use Baka\Contracts\Auth\UserInterface as AuthUserInterface;
use Kanvas\Packages\Social\Contract\Users\UserInterface;

class Users extends BaseModel implements UserInterface, AuthUserInterface
{
    public $id = 1;

    public function getId(): int
    {
        return 1;
    }

    public function getDefaultCompany(): Companies
    {
        return new Companies();
    }
}
