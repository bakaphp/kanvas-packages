<?php

namespace Kanvas\Packages\Social\Models;

use Canvas\Models\Users as KanvasUsers;
use Baka\Contracts\Auth\UserInterface;

class Users extends KanvasUsers implements UserInterface
{
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
    }
}
