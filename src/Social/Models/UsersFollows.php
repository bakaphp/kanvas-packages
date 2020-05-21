<?php

namespace Kanvas\Packages\Social\Models;

class UsersFollows extends BaseModel
{
    public $id;
    public $users_id;
    public $entity_id;
    public $entity_namespace;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'users_follows';
    }
}
