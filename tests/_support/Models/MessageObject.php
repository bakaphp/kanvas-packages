<?php

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contracts\Messages\MessagesInterface;
use Kanvas\Packages\Social\Contracts\Messages\MessageableEntityInterface;

class MessageObject implements MessagesInterface, MessageableEntityInterface
{
    public int $id = 1;

    public function getId(): int
    {
        return $this->id;
    }
}
