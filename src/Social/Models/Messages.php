<?php

namespace Kanvas\Packages\Social\Models;

class Messages extends BaseModel
{
    public $id;
    public $apps_id;
    public $companies_id;
    public $users_id;
    public $message_types_id;
    public $message;
    public $reactions_count;
    public $comments_count;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'messages';
    }
}
