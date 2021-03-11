<?php

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contract\Channels\ChannelsInterface;
use Kanvas\Packages\Social\Contract\Channels\ChannelsTrait;

class Lead extends BaseModel implements ChannelsInterface
{
    use ChannelsTrait;
    
    public int $id = 1;

    public function getId(): int
    {
        return $this->id;
    }
}
