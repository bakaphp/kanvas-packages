<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contract\Interactions;

use Kanvas\Packages\Social\Contract\Events\EventManagerAwareTrait;
use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\UsersInteractions;
use Kanvas\Packages\Social\Services\Interactions;

trait InteractionsTrait
{
    use EventManagerAwareTrait;
    
    /**
     * Interact with the object based on the action
     *
     * @param string $action
     * @return void
     */
    public function interact(string $action, $data): void
    {
        $this->fire("socialUser:{$action}", $data);
    }

    /**
     * React to an object by emoji on reaction name
     *
     * @param string $reaction
     * @return void
     */
    public function react(string $reaction): void
    {
        $this->fire("socialUser:react", $this, $reaction);
    }


    /**
     * Remove an interaction
     *
     * @param string $action
     * @param UserInterface $user
     * @return void
     */
    public function deleteInteraction(int $interactionId, UserInterface $user): void
    {
        $interaction = $this->getInteractionByUser($interactionId, $user);
        if ($interaction) {
            Interactions::removeInteraction($interaction);
        }
    }

    /**
     * Undocumented function
     *
     * @param string $action
     * @param UserInterface $user
     * @return void
     */
    public function getInteractionByType(InteractionTypesInterface $type, UserInterface $user)
    {
    }

    /**
     * Get the interaction made by the user to the current entity
     *
     * @param int $interactionId
     * @param UserInterface $user
     * @return UsersInteractions|bool
     */
    public function getInteractionByUser(int $interactionId, UserInterface $user): UsersInteractions
    {
        return $this->getInteraction([
            'conditions' => 'users_id = :userId: AND interactions_id = :interactionId: AND is_deleted = 0',
            'bind' => [
                'userId' => $user->getId(),
                'interactionId' => $interactionId
            ]
        ]);
    }
}
