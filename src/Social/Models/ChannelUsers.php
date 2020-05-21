<?php

namespace Kanvas\Packages\Social\Models;

class ChannelUsers extends BaseModel
{
    public $channel_id;
    public $users_id;
    public $messages_read_at;
    public $roles_id;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'channel_users';
    }
}
