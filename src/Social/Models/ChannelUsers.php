<?php

namespace Kanvas\Packages\Social\Models;

class ChannelUsers extends BaseModel
{
    public int $channel_id;
    public int $users_id;
    public ?string $messages_read_at = null;
    public int $roles_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('channel_users');
    }
}
