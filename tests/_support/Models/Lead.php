<?php

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contract\Channels\ChannelsInterface;

class Lead extends BaseModel implements ChannelsInterface
{
    public int $id = 1;

    public function getId(): int
    {
        return $this->id;
    }
}
