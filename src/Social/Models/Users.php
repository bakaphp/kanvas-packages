<?php

namespace Kanvas\Packages\Social\Models;

use Canvas\Models\Users as KanvasUsers;
use Phalcon\Mvc\ModelInterface;
use Baka\Http\Exception\InternalServerErrorException;
use Phalcon\Di;
use Kanvas\Packages\Social\Contract\Users\UserInterface;

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
