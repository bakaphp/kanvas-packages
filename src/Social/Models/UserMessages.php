<?php

namespace Kanvas\Packages\Social\Models;

class UserMessages extends BaseModel
{
    public $messages_id;
    public $users_id;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_messages';
    }
}
