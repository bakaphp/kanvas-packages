<?php

namespace Kanvas\Packages\Social\Models;

class MessageTags extends BaseModel
{
    public $id;
    public $message_id;
    public $tags_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('message_tags');
    }
}
