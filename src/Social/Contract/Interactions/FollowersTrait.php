<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contract\Interactions;

use Kanvas\Packages\Social\Models\UsersFollows;
use Kanvas\Packages\Social\Models\Interactions;
use Phalcon\Di;

trait FollowersTrait
{
    use TotalInteractionsTrait;

    /**
     * Get the total of following of the user
     *
     * @return integer
     */
    public function getTotalFollowing(): int
    {
        return $this->getTotal(Interactions::FOLLOWING);
    }

    /**
     * Get the total of following of the user
     *
     * @return integer
     */
    public function getTotalFollowers(): int
    {
        return $this->getTotal(Interactions::FOLLOWERS);
    }

    /**
     * Verify if the user follow the tag.
     *
     * @return boolean
     */
    public function isFollow() : bool
    {
        return (bool) UsersFollows::count([
            'conditions' => 'users_id = :userId: AND entity_id = :entityId: AND entity_namespace = :entityName: AND is_deleted = 0',
            'bind' => [
                'userId' => Di::getDefault()->get('userData')->getId(),
                'entityId' => $this->getId(),
                'entityName' => get_class($this)
            ]
        ]);
    }
}
