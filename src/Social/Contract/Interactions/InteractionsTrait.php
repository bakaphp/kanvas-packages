<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contract\Interactions;

use Baka\Contracts\Database\ModelInterface;
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
     * @param Model $data
     * @return void
     */
    public function interact(string $action, ModelInterface $data, ?string $reactionName = null): void
    {
        $this->fire("socialUser:{$action}", $data, $reactionName);
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
}
