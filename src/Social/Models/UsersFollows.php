<?php
declare(strict_types=1);

namespace Kanvas\Packages\Social\Models;

use Baka\Contracts\Auth\UserInterface;
use Kanvas\Packages\Social\Contracts\Interactions\CustomTotalInteractionsTrait;
use Phalcon\Mvc\ModelInterface;

class UsersFollows extends BaseModel
{
    use CustomTotalInteractionsTrait;

    public int $users_id;
    public int $entity_id;
    public ?int $companies_id = null;
    public ?int $companies_branches_id = null;
    public string $entity_namespace;

    /**
     * Initialize relationship after fetch
     * since we need entity_namespace info.
     *
     * @return void
     */
    public function afterFetch()
    {
        $this->hasOne(
            'entity_id',
            $this->entity_namespace,
            'id',
            [
                'reusable' => true,
                'alias' => 'entityData'
            ]
        );
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('users_follows');
    }

    /**
     * Remove the user interaction by update is_deleted.
     *
     * @return void
     */
    public function unFollow(UserInterface $userFollowing) : void
    {
        if ($this->is_deleted) {
            $this->is_deleted = 0;
            $this->saveOrFail();
            $this->increment();
            $userFollowing->increment();
        } elseif (!$this->is_deleted) {
            $this->is_deleted = 1;
            $this->saveOrFail();
            $this->decrees();
            $userFollowing->decrees();
        }
    }

    /**
     * Get the User following the entity.
     *
     * @param UserInterface $user
     * @param ModelInterface $entity
     *
     * @return self|null
     */
    public static function getByUserAndEntity(UserInterface $user, ModelInterface $entity) : ?self
    {
        return UsersFollows::findFirst([
            'conditions' => 'users_id = :user_id: AND entity_id = :entity_id: AND entity_namespace = :entity:',
            'bind' => [
                'user_id' => $user->getId(),
                'entity' => get_class($entity),
                'entity_id' => $entity->getId()
            ]
        ]);
    }

    /**
     * Lets you know if the user is following the entity.
     *
     * @return bool
     */
    public function isFollowing() : bool
    {
        return !$this->is_deleted;
    }

    /**
     * Get the interaction key.
     *
     * @return string
     */
    protected function getInteractionStorageKey() : string
    {
        return $this->entity_namespace . '-' . $this->entity_id . '-' . Interactions::FOLLOWERS;
    }
}
