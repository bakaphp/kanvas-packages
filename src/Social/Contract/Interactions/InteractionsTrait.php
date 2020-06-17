<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contract\Interactions;

use Kanvas\Packages\Social\Contract\Events\EventManagerAwareTrait;
use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\BaseModel;

trait InteractionsTrait
{
    use EventManagerAwareTrait;
    
    /**
     * Undocumented function
     *
     * @param string $action
     * @param UserInterface $user
     * @return void
     */
    public function interact(string $action, BaseModel $entity)
    {
        $this->fire("socialUser:{$action}", $entity);
    }

    /**
     * Undocumented function
     *
     * @param string $action
     * @param UserInterface $user
     * @return void
     */
    public function deleteInteraction(string $action, UserInterface $user)
    {
    }

    /**
     * Undocumented function
     *
     * @param string $action
     * @param UserInterface $user
     * @return void
     */
    public function getInteractions(string $action, UserInterface $user)
    {
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
     * Undocumented function
     *
     * @param string $action
     * @param UserInterface $user
     * @return void
     */
    public function getInteractionByUser(string $action, UserInterface $user)
    {
    }
}
