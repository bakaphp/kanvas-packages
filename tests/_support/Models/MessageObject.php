<?php
declare(strict_types=1);

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contracts\Messages\MessageableEntityInterface;
use Kanvas\Packages\Social\Contracts\Messages\MessagesInterface;

class MessageObject implements MessagesInterface, MessageableEntityInterface
{
    public int $id = 1;

    public function getId() : int
    {
        return $this->id;
    }
}
