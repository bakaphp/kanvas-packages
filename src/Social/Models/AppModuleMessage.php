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
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('app_module_message');

        $this->hasOne(
            'message_types_id',
            MessageTypes::class,
            'id',
            [
                'alias' => 'messageType',
                'params' => [
                    'conditions' => 'is_deleted = 0'
                ]
            ]
        );

        $this->hasOne(
            'message_id',
            Messages::class,
            'id',
            [
                'alias' => 'message'
            ]
        );
    }
}
