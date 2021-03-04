<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Interactions\CustomTotalInteractionsTrait;
use Kanvas\Packages\Social\Contract\Interactions\MultiInteractionsTrait;
use Phalcon\Di;
use Canvas\Models\Users;

class MessageComments extends BaseModel
{
    use CustomTotalInteractionsTrait;
    use MultiInteractionsTrait;

    public $id;
    public int $message_id;
    public int $apps_id;
    public int $companies_id;
    public int $users_id;
    public string $message;
    public int $reactions_count = 0;
    public int $parent_id = 0;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('message_comments');
        $this->belongsTo('users_id', Users::class, 'id', ['alias' => 'users']);

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

        $this->belongsTo(
            'message_id',
            Messages::class,
            'id',
            [
                'alias' => 'message',
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
    }

    /**
     * Create a comment for a message
     *
     * @param string $messageId
     * @param string $message
     * @return MessageComments
     */
    public function reply(string $message): MessageComments
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
     * Return the id of the parent in case that comment is a reply
     *
     * @return integer
     */
    public function getParentId(): int
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
     * Verify if this comment has message
     *
     * @param Messages $message
     * @return boolean
     */
    public function hasMessage(Messages $message): bool
    {
        return $this->message_id == $message->getId();
    }
}
