<?php

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contract\Users\UserInterface;

class Users implements UserInterface
{
    public $id;

    public function getId(): int
    {
        return 1;
    }

    public function getDefaultCompany(): Companies
    {
        return new Companies();
    }
}
