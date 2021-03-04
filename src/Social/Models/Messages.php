<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Interactions\CustomTotalInteractionsTrait;
use Kanvas\Packages\Social\Contract\Interactions\InteractionsTrait;
use Kanvas\Packages\Social\Contract\Interactions\TotalInteractionsTrait;
use Kanvas\Packages\Social\Contract\Messages\MessagesInterface;
use Kanvas\Packages\Social\Contract\Messages\MessageableEntityInterface;
use Phalcon\Di;
use Canvas\Traits\FileSystemModelTrait;
use Canvas\Models\SystemModules;
use Canvas\Models\Users;

class Messages extends BaseModel implements MessagesInterface, MessageableEntityInterface
{
    use CustomTotalInteractionsTrait;
    use InteractionsTrait;
    use FileSystemModelTrait;

    public $id;
    public int $apps_id;
    public int $companies_id;
    public int $users_id;
    public int $message_types_id;
    public string $message;
    public ?int $reactions_count = null;
    public ?int $comments_count = null;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('messages');
        $this->belongsTo('users_id', Users::class, 'id', ['alias' => 'users']);

        $this->hasOne(
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
                'alias' => 'comments'
            ]
        );

        $this->hasMany(
            'id',
            UsersInteractions::class,
            'entity_id',
            [
                'alias' => 'interactions',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace: AND is_deleted = 0',
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
                    'conditions' => 'entity_id = :entityId: AND entity_namespace = :namespace:',
                    'bind' => [
                        'namespace' => get_class($this),
                        'entityId' => $this->id
                    ]
                ]
            ]
        );

        $this->hasMany(
            'id',
            UsersReactions::class,
            'entity_id',
            [
                'alias' => 'reactions',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace: AND is_deleted = 0',
                    'bind' => [
                        'namespace' => get_class($this)
                    ]
                ]
            ]
        );

        $this->hasOne(
            'id',
            UsersReactions::class,
            'entity_id',
            [
                'alias' => 'reaction',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace: AND is_deleted = 0',
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

        $systemModule = SystemModules::getSystemModuleByModelName(self::class);
        $this->hasMany(
            'id',
            'Canvas\Models\FileSystemEntities',
            'entity_id',
            [
                'alias' => 'files',
                'params' => [
                    'conditions' => 'system_modules_id = ?0',
                    'bind' => [$systemModule->getId()]
                ]
            ]
        );
    }

    /**
     * Create a comment for a message
     *
     * @param string $messageId
     * @param string $message
     * @return MessageComments
     */
    public function comment(string $message): MessageComments
    {
        $comment = new MessageComments();
        $comment->message_id = $this->getId();
        $comment->apps_id = Di::getDefault()->get('app')->getId();
        $comment->companies_id = Di::getDefault()->get('userData')->getDefaultCompany()->getId();
        $comment->users_id = Di::getDefault()->get('userData')->getId();
        $comment->message = $message;
        $comment->saveOrFail();

        return $comment;
    }

    /**
     * Verify if the $userId owns this message
     *
     * @param integer $userId
     * @return boolean
     */
    public function hasUser(int $userId): bool
    {
        return $userId == $this->users_id;
    }

    /**
     * Upload Files.
     *
     * @todo move this to the baka class
     *
     * @return void
     */
    public function afterSave()
    {
        $this->associateFileSystem();
    }
}
