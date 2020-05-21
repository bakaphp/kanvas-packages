<?php

namespace Kanvas\Packages\Social\Models;

class ChannelMessages extends BaseModel
{
    public $channel_id;
    public $messages_id;
    public $users_id;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'channel_messages';
    }
}
