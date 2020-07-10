<?php

namespace Kanvas\Packages\Tests\Support\Models;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\BaseModel;

class Users extends BaseModel implements UserInterface
{
    public function getId(): int
    {
        return 1;
    }

    public function getDefaultCompany(): Companies
    {
        return new Companies();
    }
}
