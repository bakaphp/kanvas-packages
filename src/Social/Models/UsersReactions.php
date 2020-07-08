<?php

namespace Kanvas\Packages\Social\Models;

class UsersReactions extends BaseModel
{
    public $id;
    public $users_id;
    public $entity_id;
    public $entity_namespace;


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
        
        $this->setSource('users_reactions');
    }
}
