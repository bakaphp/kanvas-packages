<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contract\Interactions;

interface FollowableInterface
{
    public function isFollow() : bool;

    public function getId();
}
