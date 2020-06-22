<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Interactions\TotalInteractionsTrait;

class UsersInteractions extends BaseModel
{
    use TotalInteractionsTrait {
        getInteractionStorageKey as protected parentGetInteractionStorageKey;
    }

    public $id;
    public $users_id;
    public $entity_id;
    public $entity_namespace;
    public $interactions_id;

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
                'alias' => 'entityData',
                'params' => [
                    'conditions' => 'is_deleted = 0'
                ]
            ]
        );
    }
    
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users_interactions';
    }

    /**
     * Get the interaction key.
     *
     * @return string
     */
    protected function getInteractionStorageKey(): string
    {
        return $this->entity_namespace . '-' . $this->entity_id .'-' . $this->interactions_id;
    }
}
