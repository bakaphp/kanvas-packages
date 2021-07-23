<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Baka\Contracts\Auth\UserInterface;
use Exception;
use Kanvas\Packages\Social\Models\Interactions as InteractionsModel;
use Kanvas\Packages\Social\Models\UsersInteractions;
use Phalcon\Mvc\ModelInterface;

class Interactions
{
    /**
     * Create an interaction of a user and a entity.
     *
     * @param ModelInterface $entity
     * @param int $interactionId
     *
     * @return bool
     */
    public static function add(UserInterface $user, ModelInterface $entity, int $interactionId) : bool
    {
        $interaction = UsersInteractions::findFirst([
            'conditions' => 'users_id = :userId: AND 
                                interactions_id = :interactionId: AND 
                                entity_namespace = :namespace: AND 
                                entity_id = :entityId:',
            'bind' => [
                'userId' => $user->getId(),
                'interactionId' => $interactionId,
                'namespace' => get_class($entity),
                'entityId' => $entity->getId(),
            ]
        ]);

        if ($interaction && !InteractionsModel::isComment($interactionId)) {
            self::removeInteraction($interaction);
            return (bool) $interaction->is_deleted;
        } elseif (!$interaction) {
            $interaction = new UsersInteractions();
            $interaction->users_id = $user->getId();
            $interaction->entity_namespace = get_class($entity);
            $interaction->entity_id = $entity->getId();
            $interaction->interactions_id = $interactionId;
            $interaction->created_at = date('Y-m-d H:i:s');
            $interaction->saveOrFail();
        }
        $interaction->increment();
        return (bool) $interaction->is_deleted;
    }

    /**
     * Get interaction object by its name.
     *
     * @param string $interactionName
     *
     * @return InteractionsModel
     */
    public static function getInteractionByName(string $interactionName) : InteractionsModel
    {
        return InteractionsModel::findFirstOrFail([
            'conditions' => 'name = :name: AND is_deleted = 0',
            'bind' => [
                'name' => $interactionName
            ]
        ]);
    }

    /**
     * Remove the user interaction by update is_deleted.
     *
     * @return void
     */
    public static function removeInteraction(UsersInteractions $interaction) : void
    {
        if ($interaction->is_deleted) {
            $interaction->is_deleted = 0;
            $interaction->saveOrFail();
            $interaction->increment();
        } else {
            $interaction->is_deleted = 1;
            $interaction->saveOrFail();
            $interaction->decrees();
        }
    }

    /**
     * Return the ID correspondent to the interaction type and
     * throws and exception if doesn't exist.
     *
     * @param string $interactionName
     *
     * @throws Exception
     *
     * @return int
     */
    public static function getInteractionIdByName(string $interactionName) : int
    {
        switch ($interactionName) {
            case 'react':
                return InteractionsModel::REACT;
                break;

            case 'save':
                return InteractionsModel::SAVE;
                break;

            case 'comment':
                return InteractionsModel::COMMENT;
                break;

            case 'following':
                return InteractionsModel::FOLLOWING;
                break;

            case 'followers':
                return InteractionsModel::FOLLOWERS;
                break;

            default:
                throw new Exception('Interaction name not found');
                break;
        }
    }
}
