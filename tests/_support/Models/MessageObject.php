<?php

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contract\Messages\MessagesInterface;

class MessageObject implements MessagesInterface
{
    public int $id = 1;

    public function getId(): int
    {
        return $this->id;
    }
}
