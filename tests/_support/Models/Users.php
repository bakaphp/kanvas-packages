<?php

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contracts\Interactions\FollowableInterface;
use Kanvas\Packages\Social\Contracts\Interactions\FollowersTrait;
use Baka\Contracts\Auth\UserInterface;
use Canvas\Models\Users as ModelsUsers;

class Users extends ModelsUsers implements FollowableInterface
{
    use FollowersTrait;
}
