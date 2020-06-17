<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contract\Users;

use Phalcon\Mvc\ModelInterface;

interface UserInterface
{
    public function getDefaultCompany(): ModelInterface;

    public function getId(): int;
}
