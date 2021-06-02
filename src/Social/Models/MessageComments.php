<?php

namespace Kanvas\Packages\Social\Models;

use Canvas\Contracts\FileSystemModelTrait;
use Canvas\Models\Users;
use Kanvas\Packages\Social\Contracts\Interactions\CustomTotalInteractionsTrait;
use Kanvas\Packages\Social\Contracts\Interactions\MultiInteractionsTrait;
use Kanvas\Packages\Social\ElasticDocuments\Messages as ElasticMessagesDocument;
use Kanvas\Packages\Social\Jobs\ElasticMessages;
use Phalcon\Di;

class MessageComments extends BaseModel
{
    use CustomTotalInteractionsTrait;
    use MultiInteractionsTrait;
    use FileSystemModelTrait;

    public $id;
    public int $message_id;
    public int $apps_id;
    public int $companies_id;
    public int $users_id = 0;
    public string $message = '';
    public int $reactions_count = 0;
    public int $parent_id = 0;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('message_comments');
        $this->belongsTo('users_id', Users::class, 'id', ['alias' => 'users', 'reusable' => true, ]);

        $this->hasMany(
            'id',
            UsersInteractions::class,
            'entity_id',
            [
                'reusable' => true,
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
                'reusable' => true,
                'alias' => 'interaction',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace:',
                    'bind' => [
                        'namespace' => get_class($this)
                    ]
                ]
            ]
        );

        $this->belongsTo(
            'message_id',
            Messages::class,
            'id',
            [
                'reusable' => true,
                'alias' => 'messages',
                'params' => [
                    'conditions' => 'is_deleted = 0'
                ]
            ]
        );

        $this->hasMany(
            'id',
            UsersReactions::class,
            'entity_id',
            [
                'reusable' => true,
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
                'reusable' => true,
                'alias' => 'reaction',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace: AND is_deleted = 0',
                    'bind' => [
                        'namespace' => get_class($this)
                    ]
                ]
            ]
        );
    }

    /**
     * Create a comment for a message.
     *
     * @param string $messageId
     * @param string $message
     *
     * @return MessageComments
     */
    public function reply(string $message) : MessageComments
    {
        $comment = new MessageComments();
        $comment->message_id = $this->message_id;
        $comment->apps_id = Di::getDefault()->get('app')->getId();
        $comment->companies_id = Di::getDefault()->get('userData')->getDefaultCompany()->getId();
        $comment->users_id = Di::getDefault()->get('userData')->getId();
        $comment->message = $message;
        $comment->parent_id = $this->getParentId();
        $comment->saveOrFail();

        return $comment;
    }

    /**
     * Return the id of the parent in case that comment is a reply.
     *
     * @return int
     */
    public function getParentId() : int
    {
        return $this->parent_id == 0 ? $this->getId() : $this->parent_id;
    }

    /**
     * Check if comment is parent.
     *
     * @return bool
     */
    public function isParent() : bool
    {
        return $this->parent_id == 0;
    }

    /**
     * Verify if this comment has message.
     *
     * @param Messages $message
     *
     * @return bool
     */
    public function hasMessage(Messages $message) : bool
    {
        return $this->message_id == $message->getId();
    }

    /**
     * After update.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->associateFileSystem();
        $elasticMessage = new ElasticMessagesDocument();
        $elasticMessage->updateCommentsCount($this->messages);
        ElasticMessages::dispatch($this->messages);
    }
}
