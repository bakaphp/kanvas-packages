<?php

namespace Kanvas\Packages\Social\Models;

use Baka\Contracts\Auth\UserInterface;
use Canvas\Contracts\FileSystemModelTrait;
use Canvas\Models\Behaviors\Uuid;
use Canvas\Models\SystemModules;
use Canvas\Models\Users;
use Kanvas\Packages\Social\Contracts\Interactions\CustomTotalInteractionsTrait;
use Kanvas\Packages\Social\Contracts\Interactions\InteractionsTrait;
use Kanvas\Packages\Social\Contracts\Messages\MessageableEntityInterface;
use Kanvas\Packages\Social\Contracts\Messages\MessagesInterface;
use Kanvas\Packages\Social\Jobs\ElasticMessages;
use Phalcon\Di;

class Messages extends BaseModel implements MessagesInterface, MessageableEntityInterface
{
    use CustomTotalInteractionsTrait;
    use InteractionsTrait;
    use FileSystemModelTrait;

    public string $uuid;
    public int $apps_id;
    public int $companies_id;
    public int $users_id;
    public int $message_types_id;
    public string $message;
    public ?int $reactions_count = 0;
    public ?int $comments_count = 0;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('messages');
        $this->belongsTo('users_id', Users::class, 'id', ['alias' => 'users']);

        $this->addBehavior(
            new Uuid()
        );

        $this->hasOne(
            'id',
            AppModuleMessage::class,
            'message_id',
            [
                'reusable' => true,
                'alias' => 'appModuleMessage'
            ]
        );

        $this->hasMany(
            'id',
            MessageVariables::class,
            'message_id',
            [
                'reusable' => true,
                'alias' => 'messageVariables'
            ]
        );

        $this->hasMany(
            'id',
            MessageComments::class,
            'message_id',
            [
                'reusable' => true,
                'alias' => 'comments'
            ]
        );

        $this->hasMany(
            'id',
            UsersInteractions::class,
            'entity_id',
            [
                'reusable' => true,
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
                'reusable' => true,
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

        $this->hasOne(
            'message_types_id',
            MessageTypes::class,
            'id',
            [
                'reusable' => true,
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
                'reusable' => true,
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
                'reusable' => true,
                'alias' => 'channels'
            ]
        );

        $systemModule = SystemModules::getByModelName(self::class, false);
        $this->hasMany(
            'id',
            'Canvas\Models\FileSystemEntities',
            'entity_id',
            [
                'reusable' => true,
                'alias' => 'files',
                'params' => [
                    'conditions' => 'system_modules_id = ?0',
                    'bind' => [$systemModule->getId()]
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
    public function comment(string $message, UserInterface $user) : MessageComments
    {
        $comment = new MessageComments();
        $comment->message_id = $this->getId();
        $comment->apps_id = Di::getDefault()->get('app')->getId();
        $comment->companies_id = $user->getDefaultCompany()->getId();
        $comment->users_id = $user->getId();
        $comment->message = $message;
        $comment->saveOrFail();

        return $comment;
    }

    /**
     * Verify if the $userId owns this message.
     *
     * @param int $userId
     *
     * @return bool
     */
    public function hasUser(int $userId) : bool
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
        ElasticMessages::dispatch($this);
    }
}
