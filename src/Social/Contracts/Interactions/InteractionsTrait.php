<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contracts\Interactions;

use Baka\Contracts\Auth\UserInterface;
use Baka\Contracts\Database\ModelInterface;
use Kanvas\Packages\Social\Contracts\Events\EventManagerAwareTrait;
use Kanvas\Packages\Social\Models\UsersInteractions;
use Kanvas\Packages\Social\Services\Interactions;

trait InteractionsTrait
{
    use EventManagerAwareTrait;

    /**
     * Interact with the object based on the action.
     *
     * @param string $action
     * @param Model $data
     *
     * @return void
     */
    public function interact(string $action, ModelInterface $data, ?string $reactionName = null) : void
    {
        $this->fire("socialUser:{$action}", $data, $reactionName);
    }

    /**
     * Remove an interaction.
     *
     * @param string $action
     * @param UserInterface $user
     *
     * @return void
     */
    public function deleteInteraction(int $interactionId, UserInterface $user) : void
    {
        $interaction = $this->getInteractionByUser($interactionId, $user);
        if ($interaction) {
            Interactions::removeInteraction($interaction);
        }
    }

    /**
     * Undocumented function.
     *
     * @param string $action
     * @param UserInterface $user
     *
     * @return void
     */
    public function getInteractionByType(InteractionTypesInterface $type, UserInterface $user)
    {
    }

    /**
     * Get the current user interaction.
     *
     * @param int $interactionId
     * @param UserInterface $user
     *
     * @return UsersInteractions|null
     */
    public function getInteractionByUser(int $interactionId, UserInterface $user) : ?UsersInteractions
    {
        return UsersInteractions::findFirst([
            'conditions' => '
                entity_id = :entity_id: 
                AND entity_namespace = :entity_namespace: 
                AND interactions_id  = :interactions_id:
                AND users_id = :users_id:',
            'bind' => [
                'entity_id' => $this->getId(),
                'entity_namespace' => get_class($this),
                'interactions_id' => $interactionId,
                'users_id' => $user->getId()
            ]
        ]);
    }
}
