<?php

namespace Kanvas\Packages\Social\Models;

class Channels extends BaseModel
{
    public $id;
    public $name;
    public $description;
    public $last_message_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('channels');

        $this->hasManyToMany(
            'id',
            ChannelMessages::class,
            'channel_id',
            'messages_id',
            Messages::class,
            [
                'alias' => 'messages'
            ]
        );
    }

    /**
     * Get Channel by name
     *
     * @param string $channelName
     * @return self
     */
    public static function getByName(string $channelName): self
    {
        return self::findFirstOrFail([
            'conditions' => 'name = :channelName: AND is_deleted = 0',
            'bind'=> [
                'channelName' => $channelName
            ]
        ]);
    }
}
