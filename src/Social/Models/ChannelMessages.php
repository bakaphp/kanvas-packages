<?php

namespace Kanvas\Packages\Social\Models;

class ChannelMessages extends BaseModel
{
    public int $channel_id;
    public int $messages_id;
    public int $users_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        
        $this->setSource('channel_messages');
    }
}
