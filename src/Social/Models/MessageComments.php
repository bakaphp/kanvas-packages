<?php

namespace Kanvas\Packages\Social\Models;

class MessageComments extends BaseModel
{
    public $id;
    public $message_id;
    public $apps_id;
    public $companies_id;
    public $users_id;
    public $message;
    public $reactions_count;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'message_comments';
    }
}
