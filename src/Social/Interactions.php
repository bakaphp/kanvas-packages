<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social;

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
    public static function add(UserInterface $user, ModelInterface $entity, string $interactionName) : bool
    {
        $interaction = InteractionsModel::getByName($interactionName);

        $userInteraction = UsersInteractions::getByEntityInteraction($user, $entity, $interaction);

        if ($userInteraction && !InteractionsModel::isComment($interaction->getId())) {
            self::removeInteraction($userInteraction);
            return (bool) $userInteraction->is_deleted;
        } elseif (!$userInteraction) {
            $userInteraction = new UsersInteractions();
            $userInteraction->users_id = $user->getId();
            $userInteraction->entity_namespace = get_class($entity);
            $userInteraction->entity_id = $entity->getId();
            $userInteraction->interactions_id = $interaction->getId();
            $userInteraction->created_at = date('Y-m-d H:i:s');
            $userInteraction->saveOrFail();
        }

        return (bool) $userInteraction->is_deleted;
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
        } else {
            $interaction->is_deleted = 1;
            $interaction->saveOrFail();
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
     * @deprecated version 0.4
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
