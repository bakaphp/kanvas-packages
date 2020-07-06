<?php

namespace Kanvas\Packages\Social\Models;

class ChannelUsers extends BaseModel
{
    public $channel_id;
    public $users_id;
    public $messages_read_at;
    public $roles_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('channel_users');
    }
}
