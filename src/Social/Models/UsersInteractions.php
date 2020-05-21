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
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users_interactions';
    }
}
