<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contracts\Interactions;

interface FollowableInterface
{
    public function isFollow() : bool;

    public function getId();
}
