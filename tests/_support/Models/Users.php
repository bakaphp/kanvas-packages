<?php

namespace Kanvas\Packages\Test\Support\Models;

use Baka\Contracts\Auth\UserInterface as AuthUserInterface;
use Kanvas\Packages\Social\Contracts\Interactions\FollowableInterface;
use Kanvas\Packages\Social\Contracts\Interactions\FollowersTrait;
use Kanvas\Packages\Social\Contracts\Users\UserInterface;

class Users extends BaseModel implements UserInterface, AuthUserInterface, FollowableInterface
{
    use FollowersTrait;

    public $id = 1;

    public function getId(): int
    {
        return $this->id;
    }

    public function getDefaultCompany(): Companies
    {
        return new Companies();
    }
}
