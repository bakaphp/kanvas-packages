<?php

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contract\Messages\MessageableInterface;

class MessageObject implements MessageableInterface
{
    public int $id = 1;

    public function getId(): int
    {
        return $this->id;
    }
}
