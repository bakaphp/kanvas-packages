<?php

namespace Kanvas\Packages\Social\Models;

class MessageTags extends BaseModel
{
    public $id;
    public int $message_id;
    public int $tags_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('message_tags');
    }
}
