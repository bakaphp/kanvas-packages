<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contracts\Interactions;

use Baka\Contracts\Auth\UserInterface;

trait MultiInteractionsTrait
{
    use InteractionsTrait {
        deleteInteraction as protected parentDeleteInteraction;
    }

    /**
     * Remove the user interaction if the user not have more actions.
     *
     * @param string $action
     * @param UserInterface $user
     *
     * @return void
     */
    public function deleteInteraction(int $interactionId, UserInterface $user) : void
    {
        if (!$this->hasInteraction($user)) {
            $this->parentDeleteInteraction($interactionId, $user);
        }
    }

    /**
     * Verify if the user have an interaction to the same message.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    protected function hasInteraction(UserInterface $user) : bool
    {
        return (bool) $this->count(
            [
                'conditions' => 'message_id = :messageId: AND users_id = :userId: AND is_deleted = 0',
                'bind' => [
                    'messageId' => $this->message_id,
                    'userId' => $user->getId()
                ]
            ]
        );
    }
}
