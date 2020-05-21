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

        $this->hasOne(
            'id',
            UsersFollows::class,
            'entity_id',
            [
                'alias' => 'follow',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace:',
                    'bind' => [
                        'namespace' => get_class($this)
                    ]
                ]
            ]
        );
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'channels';
    }
}
