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
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('message_types');

        $this->hasMany(
            'id',
            Messages::class,
            'message_types_id',
            [
                'alias' => 'messages',
                'params' => [
                    'conditions' => 'is_deleted = 0'
                ]
            ]
        );

        $this->hasMany(
            'id',
            AppModuleMessage::class,
            'message_types_id',
            [
                'alias' => 'appModules'
            ]
        );
    }
}
