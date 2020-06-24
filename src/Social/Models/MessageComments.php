<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Interactions\CustomTotalInteractionsTrait;
use Kanvas\Packages\Social\Contract\Interactions\InteractionsTrait;
use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Phalcon\Di;

class MessageComments extends BaseModel
{
    use CustomTotalInteractionsTrait;

    use InteractionsTrait {
        deleteInteraction as public parentDeleteInteraction;
    }

    public $id;
    public $message_id;
    public $apps_id;
    public $companies_id;
    public $users_id;
    public $message;
    public $reactions_count;
    public $parent_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('message_comments');

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
    }
    
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'message_comments';
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
     * WIP delete the interaction of the comment
     *
     * @param string $action
     * @param UserInterface $user
     * @return void
     */
    public function deleteInteraction(string $action, UserInterface $user)
    {
        $interaction = $this->getInteractionByUser($action, $user);
        if ($interaction) {
            Interactions::removeInteraction($interaction);
        }
    }
}
