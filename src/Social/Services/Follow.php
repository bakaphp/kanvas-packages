<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Packages\Social\Models\UsersFollows;
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
    public static function userFollow(UserInterface $userFollowing, ModelInterface $entity) : bool
    {
        return self::follow($userFollowing, $entity);
    }

    /**
     * Allow a User to follow a entity.
     *
     * @param UserInterface $userFollowing
     * @param ModelInterface $entity
     *
     * @return bool
     */
    public static function follow(UserInterface $userFollowing, ModelInterface $entity) : bool
    {
        $follow = UsersFollows::getByUserAndEntity($userFollowing, $entity);

        if ($follow) {
            $follow->unFollow($userFollowing);
            return $follow->isFollowing();
        }

        $follow = new UsersFollows();
        $follow->users_id = $userFollowing->getId();
        $follow->entity_id = $entity->getId();
        $follow->entity_namespace = get_class($entity);
        $follow->saveOrFail();
        $follow->increment();
        $userFollowing->increment();

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
    public static function unFollow(UserInterface $userFollowing, ModelInterface $entity) : bool
    {
        $follow = UsersFollows::getByUserAndEntity($userFollowing, $entity);


        if ($follow) {
            $follow->unFollow($userFollowing);
            return $follow->isFollowing();
        }

        return false;
    }

    /**
     * Is a user following an entity?
     *
     * @param UserInterface $userFollowing
     * @param ModelInterface $entity
     *
     * @return bool
     */
    public static function following(UserInterface $userFollowing, ModelInterface $entity) : bool
    {
        return (bool) UsersFollows::count([
            'conditions' => 'users_id = :userId: AND entity_id = :entityId: AND entity_namespace = :entityName: AND is_deleted = 0',
            'bind' => [
                'userId' => $userFollowing->getId(),
                'entityId' => $entity->getId(),
                'entityName' => get_class($entity)
            ]
        ]);
    }
}
