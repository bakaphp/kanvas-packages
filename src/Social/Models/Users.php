<?php
declare(strict_types=1);

namespace Kanvas\Packages\Social\Models;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Models\Users as KanvasUsers;

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
