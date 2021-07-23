<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Packages\Social\Models\UsersFollows;
use Phalcon\Di;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Mvc\ModelInterface;

class Follow
{
    /**
     * Return the data of entities that the user follows.
     *
     * @param UserInterface $user
     * @param ModelInterface $entity
     *
     * @return Simple
     */
    public static function getFollowsByUser(UserInterface $user, ModelInterface $entity) : Simple
    {
        $userFollows = UsersFollows::find([
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
     *
     * @return bool
     */
    public static function userFollow(UserInterface $user, ModelInterface $entity) : bool
    {
        return self::follow($user, $entity);
    }

    /**
     * Allow a User to follow a entity.
     *
     * @param UserInterface $userFollowing
     * @param ModelInterface $entity
     *
     * @return bool
     */
    public static function follow(UserInterface $user, ModelInterface $entity) : bool
    {
        $follow = UsersFollows::getByUserAndEntity($user, $entity);

        if ($follow) {
            $follow->unFollow($user);
            return $follow->isFollowing();
        }

        //global following means we don't take into account the current user company
        $globalFollowing = Di::getDefault()->get('config')->social->global_following;

        $follow = new UsersFollows();
        $follow->users_id = $user->getId();
        $follow->entity_id = $entity->getId();
        $follow->entity_namespace = get_class($entity);
        $follow->companies_id = $globalFollowing ? 0 : $user->getDefaultCompany()->getId();
        $follow->companies_branches_id = $globalFollowing ? 0 : $user->currentBranchId();
        $follow->saveOrFail();

        $follow->increment();
        $user->increment();

        return $follow->isFollowing();
    }

    /**
     * Unfollow an entity.
     *
     * @param UserInterface $userFollowing
     * @param ModelInterface $entity
     *
     * @return bool
     */
    public static function unFollow(UserInterface $user, ModelInterface $entity) : bool
    {
        return self::follow($user, $entity);
    }

    /**
     * Is a user following an entity?
     *
     * @param UserInterface $userFollowing
     * @param ModelInterface $entity
     *
     * @return bool
     */
    public static function following(UserInterface $user, ModelInterface $entity) : bool
    {
        return (bool) UsersFollows::count([
            'conditions' => 'users_id = :userId: AND entity_id = :entityId: AND entity_namespace = :entityName: AND is_deleted = 0',
            'bind' => [
                'userId' => $user->getId(),
                'entityId' => $entity->getId(),
                'entityName' => get_class($entity)
            ]
        ]);
    }
}
