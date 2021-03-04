<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Interactions\CustomTotalInteractionsTrait;
use Kanvas\Packages\Social\Contract\Users\UserInterface;

class UsersFollows extends BaseModel
{
    use CustomTotalInteractionsTrait;

    public $id;
    public int $users_id;
    public int $entity_id;
    public ?int $companies_id = null;
    public ?int $companies_branches_id = null;
    public string $entity_namespace;


    /**
     * Initialize relationshit after fetch
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
            $this->decrese();
            $userFollowing->decrese();
        }
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
