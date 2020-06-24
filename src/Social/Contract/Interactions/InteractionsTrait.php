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
     * Undocumented function
     *
     * @param string $action
     * @return void
     */
    public function interact(string $action): void
    {
        $this->fire("socialUser:{$action}", $this);
    }

    /**
     * Remove an interaction
     *
     * @param string $action
     * @param UserInterface $user
     * @return void
     */
    public function deleteInteraction(string $action, UserInterface $user): void
    {
        $interaction = $this->getInteractionByUser($action, $user);
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
     * @param string $action
     * @param UserInterface $user
     * @return UsersInteractions|bool
     */
    public function getInteractionByUser(string $action, UserInterface $user): UsersInteractions
    {
        return $this->getInteraction([
            'conditions' => 'users_id = :userId: AND interactions_id = :interactionId: AND is_deleted = 0',
            'bind' => [
                'userId' => $user->getId(),
                'interactionId' => Interactions::getInteractionIdByName($action)
            ]
        ]);
    }
}
