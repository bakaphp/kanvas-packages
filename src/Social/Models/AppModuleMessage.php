<?php

namespace Kanvas\Packages\Social\Models;

class AppModuleMessage extends BaseModel
{
    public $id;
    public $message_id;
    public $message_types_id;
    public $apps_id;
    public $companies_id;
    public $system_modules_id;
    public $entity_id;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'app_module_message';
    }
}
