<?php

namespace Kanvas\Packages\Social\Models;

class MessageTags extends BaseModel
{
    public $id;
    public $message_id;
    public $tags_id;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'message_tags';
    }
}
