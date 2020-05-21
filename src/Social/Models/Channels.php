<?php

namespace Kanvas\Packages\Social\Models;

class Channels extends BaseModel
{
    public $id;
    public $name;
    public $description;
    public $last_message_id;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'channels';
    }
}
