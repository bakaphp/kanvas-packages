<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Exception;
use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\Interactions as InteractionsModel;
use Kanvas\Packages\Social\Models\UsersInteractions;
use Phalcon\Mvc\ModelInterface;

class Interactions
{
    /**
     * Create an interaction of a user and a entity.
     *
     * @param ModelInterface $entity
     * @param integer $interactionId
     * @return UsersInteractions
     */
    public static function add(UserInterface $user, ModelInterface $entity, int $interactionId): bool
    {
        $interaction = $entity->getInteraction([
            'conditions' => 'users_id = :user_id: AND interactions_id = :interaction_id:',
            'bind' => [
                'user_id' => $user->getId(),
                'interaction_id' => $interactionId,
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
     * Get interaction object by its name
     *
     * @param string $interactionName
     * @return UsersInteractions
     */
    public static function getInteractionByName(string $interactionName): UsersInteractions
    {
        return UsersInteractions::findOrFail([
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
    public static function removeInteraction(UsersInteractions $interaction): void
    {
        if ($interaction->is_deleted) {
            $interaction->is_deleted = 0;
            $interaction->saveOrFail();
            $interaction->increment();
        } else {
            $interaction->is_deleted = 1;
            $interaction->saveOrFail();
            $interaction->decrese();
        }
    }


    /**
     * Return the ID correspondent to the feedType and
     * throws and exception if doesnt exist.
     *
     * @param string $interactionName
     * @throws Exception
     * @return integer
     */
    public static function getInteractionIdByName(string $interactionName): int
    {
        switch ($interactionName) {
            case 'like':
                return InteractionsModel::LIKE;
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