<?php

namespace Kanvas\Packages\Social\Models;

class UsersInteractions extends BaseModel
{
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
}
