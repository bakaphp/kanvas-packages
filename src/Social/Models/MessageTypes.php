<?php

namespace Kanvas\Packages\Social\Models;

class MessageTypes extends BaseModel
{
    public $id;
    public $apps_id;
    public $languages_id;
    public $name;
    public $verb;
    public $template;
    public $templates_plura;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'message_types';
    }
}
