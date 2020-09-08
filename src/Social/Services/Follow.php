<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\BaseModel;
use Kanvas\Packages\Social\Models\UsersFollows;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Mvc\Model\Resultset\Simple;

class Follow
{
    /**
     * Return the data of entities that the user follows
     *
     * @param UserInterface $user
     * @param ModelInterface $entity
     * @return Simple
     */
    public static function getFollowsByUser(UserInterface $user, ModelInterface $entity): Simple
    {
        $userFollows = UsersFollows::findOrFail([
            'conditions' => 'users_id = :user_id: AND entity_namespace = :entity: AND is_deleted = 0',
            'bind' => [
                'user_id' => $user->getId(),
                'entity' => get_class($entity),
            ]
        ]);

        return $userFollows;
    }

    /**
     * Follow and unfollow an entity if its exist.
     *
     * @param UserInterface $userFollowing User that is following
     * @param ModelInterface $entity Entity that is being followed
     * @return boolean
     */
    public static function userFollow(UserInterface $userFollowing, ModelInterface $entity): bool
    {
        $follow = UsersFollows::findFirstOrCreate(
            [
                'conditions' => 'users_id = :user_id: AND entity_id = :entity_id: AND entity_namespace = :entity:',
                'bind' => [
                    'user_id' => $userFollowing->getId(),
                    'entity' => get_class($entity),
                    'entity_id' => $entity->getId()
                ]
            ],
            [
                'users_id' => $userFollowing->getId(),
                'entity_id'=> $entity->getId(),
                'entity_namespace' => get_class($entity),
            ]
        );

        if ($follow) {
            $follow->unFollow($userFollowing);
            return (bool) $follow->is_deleted;
        }
        $follow->increment();
        $userFollowing->increment();

        return (bool) $follow->is_deleted;
    }
}
