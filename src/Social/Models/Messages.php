<?php

namespace Kanvas\Packages\Social\Models;

class Messages extends BaseModel
{
    public $id;
    public $apps_id;
    public $companies_id;
    public $users_id;
    public $message_types_id;
    public $message;
    public $reactions_count;
    public $comments_count;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('messages');

        $this->hasMany(
            'id',
            AppModuleMessage::class,
            'message_id',
            [
                'alias' => 'appModuleMessage'
            ]
        );

        $this->hasMany(
            'id',
            MessageVariables::class,
            'message_id',
            [
                'alias' => 'messageVariables'
            ]
        );

        $this->hasMany(
            'id',
            MessageComments::class,
            'message_id',
            [
                'alias' => 'messageComments'
            ]
        );

        $this->hasMany(
            'id',
            UsersInteractions::class,
            'entity_id',
            [
                'alias' => 'interactions',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace:',
                    'bind' => [
                        'namespace' => get_class($this)
                    ]
                ]
            ]
        );

        $this->hasOne(
            'id',
            UsersInteractions::class,
            'entity_id',
            [
                'alias' => 'interaction',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace:',
                    'bind' => [
                        'namespace' => get_class($this)
                    ]
                ]
            ]
        );

        $this->hasOne(
            'message_types_id',
            MessageTypes::class,
            'id',
            [
                'alias' => 'message_type'
            ]
        );

        $this->hasManyToMany(
            'id',
            MessageTags::class,
            'message_id',
            'tags_id',
            Tags::class,
            'id',
            [
                'alias' => 'tags'
            ]
        );

        $this->hasManyToMany(
            'id',
            ChannelMessages::class,
            'messages_id',
            'channel_id',
            Channels::class,
            'id',
            [
                'alias' => 'channels'
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
        return 'messages';
    }

    /**
     * Create a channel for the current message
     *
     * @param string $distribution
     * @return void
     */
    public function addDistributionChannel(string $distribution): void
    {
        $channelMessage = new ChannelMessages();
        $channelMessage->messages_id = $this->getId();
        $channelMessage->users_id = $this->users_id;
        $channelMessage->channel_id = Channels::getByName($distribution)->getId();
        $channelMessage->saveOrFail();
    }
}
