<?php

namespace Kanvas\Packages\Test\Support\Models;

use Baka\Contracts\Auth\UserInterface as AuthUserInterface;
use Kanvas\Packages\Social\Contract\Interactions\FollowableInterface;
use Kanvas\Packages\Social\Contract\Interactions\FollowersTrait;
use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Canvas\Models\Users as KanvasUsers;
use Canvas\Models\SystemModules;

class Users extends KanvasUsers implements UserInterface, AuthUserInterface, FollowableInterface
{
    use FollowersTrait;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $systemModule = SystemModules::getByModelName(self::class, false);
    }
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
