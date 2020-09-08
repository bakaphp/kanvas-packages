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
     * @param BaseModel $categoryEntity
     * @param Users $user
     * @return array
     */
    public static function getFollowsByUser(UserInterface $user, ModelInterface $entity): Simple
    {
        $followsData = [];

        $userFollows = UsersFollows::findOrFail([
            'conditions' => 'users_id = :user_id: AND entity_namespace = :entity: AND is_deleted = 0',
            'bind' => [
                'user_id' => $user->getId(),
                'entity' => get_class($entity),
            ]
        ]);

        foreach ($userFollows as $userFollow) {
            $followsData[] = $userFollow->getEntityData();
        }

        return $userFollows;
    }

    /**
     * Follow and unfollow an entity if its exist.
     *
     * @param Users $userFollowing User that is following
     * @param Model $entity Entity that is being followed
     * @return boolean
     */
    public static function userFollow(UserInterface $userFollowing, ModelInterface $entity): bool
    {
        $follow = UsersFollows::findFirst([
            'conditions' => 'users_id = :user_id: AND entity_id = :entity_id: AND entity_namespace = :entity:',
            'bind' => [
                'user_id' => $userFollowing->getId(),
                'entity' => get_class($entity),
                'entity_id' => $entity->getId()
            ]
        ]);

        if ($follow) {
            $follow->unFollow($userFollowing);
            return (bool) $follow->is_deleted;
        }
        $follow = new UsersFollows();
        $follow->users_id = $userFollowing->getId();
        $follow->entity_id = $entity->getId();
        $follow->entity_namespace = get_class($entity);
        $follow->saveOrFail();
        $follow->increment();
        $userFollowing->increment();

        return (bool) $follow->is_deleted;
    }
}
