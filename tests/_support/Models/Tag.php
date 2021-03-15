<?php

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contracts\Interactions\FollowableInterface;
use Kanvas\Packages\Social\Contracts\Interactions\FollowersTrait;

class Tag extends BaseModel implements FollowableInterface
{
    use FollowersTrait;

    public int $id = 1;

    public function getId(): int
    {
        return $this->id;
    }
}
