<?php

namespace Kanvas\Packages\Social\Models;

class MessageVariables extends BaseModel
{
    public $id;
    public $message_id;
    public $key;
    public $value;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'message_variables';
    }
}
