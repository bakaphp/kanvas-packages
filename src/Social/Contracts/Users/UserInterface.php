<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contracts\Users;

interface UserInterface
{
    public function getDefaultCompany();

    public function getId();
}
