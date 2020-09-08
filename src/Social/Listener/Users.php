<?php
declare(strict_types=1);
namespace Kanvas\Packages\Social\Listener;

use Kanvas\Packages\Social\Services\Interactions;
use Kanvas\Packages\Social\Models\Interactions as InteractionsModel;
use Kanvas\Packages\Social\Services\Follow;
use Kanvas\Packages\Social\Services\Reactions;
use Phalcon\Di;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Events\Event;

class Users
{
    /**
     * Create a save interaction between the user and the entity
     *
     * @param Event $event
     * @param ModelInterface $entity
     * @return void
     */
    public function save(Event $event, ModelInterface $entity, InteractionsModel $interactions): void
    {
        Interactions::add(Di::getDefault()->get('userData'), $entity, InteractionsModel::SAVE);
    }

    /**
     * Add an reaction to the entity
     *
     * @param Event $event
     * @param ModelInterface $entity
     * @param string $reaction
     * @return void
     */
    public function react(Event $event, ModelInterface $entity, string $reaction): void
    {
        Interactions::add(Di::getDefault()->get('userData'), $entity, InteractionsModel::REACT);
        Reactions::addMessageReaction($reaction, Di::getDefault()->get('userData'), $entity);
    }

    /**
     * Create a comment interaction between the user and the entity
     *
     * @param Event $event
     * @param ModelInterface $entity
     * @return void
     */
    public function comment(Event $event, ModelInterface $entity): void
    {
        Interactions::add(Di::getDefault()->get('userData'), $entity, InteractionsModel::COMMENT);
    }

    /**
     * Create a reply interaction between the user and the entity
     *
     * @param Event $event
     * @param ModelInterface $entity
     * @return void
     */
    public function reply(Event $event, ModelInterface $entity): void
    {
        Interactions::add(Di::getDefault()->get('userData'), $entity, InteractionsModel::REPLIED);
    }

    /**
     * Create a follow interaction between the user and the entity
     *
     * @param Event $event
     * @param ModelInterface $entity
     * @return void
     */
    public function followw(Event $event, ModelInterface $entity): void
    {
        $user = Di::getDefault()->get('userData');
        $user->interaction_type_id = InteractionsModel::FOLLOWING;

        Follow::userFollow($user, $entity);
    }
}
