<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\BaseModel;
use Kanvas\Packages\Social\Models\Interactions as InteractionsModel;
use Kanvas\Packages\Social\Models\UsersInteractions;

class Interactions
{
    /**
     * Create an interaction of a user and a entity.
     *
     * @param BaseModel $entity
     * @param integer $interactionId
     * @return UsersInteractions
     */
    public static function add(UserInterface $user, BaseModel $entity, int $interactionId): bool
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
}
