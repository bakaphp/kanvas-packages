<?php
declare(strict_types=1);

namespace Kanvas\Packages\Test\Support\Models;

use Canvas\Models\Users as ModelsUsers;
use Kanvas\Packages\Social\Contracts\Interactions\FollowableInterface;
use Kanvas\Packages\Social\Contracts\Interactions\FollowersTrait;

class Users extends ModelsUsers implements FollowableInterface
{
    use FollowersTrait;
}
