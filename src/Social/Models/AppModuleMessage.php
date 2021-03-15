<?php

namespace Kanvas\Packages\Social\Models;

class AppModuleMessage extends BaseModel
{
    public int $message_id;
    public int $message_types_id;
    public int $apps_id;
    public int $companies_id;
    public ?string $system_modules = null;
    public ?int $entity_id = null;

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
                'reusable' => true,
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
                'reusable' => true,
                'alias' => 'message'
            ]
        );
    }
}
