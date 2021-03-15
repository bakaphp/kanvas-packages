<?php

namespace Kanvas\Packages\Social\Models;

class MessageVariables extends BaseModel
{
    public $id;
    public string $message_id;
    public string $key;
    public string $value;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('message_variables');

        $this->belongsTo(
            'message_id',
            Messages::class,
            'id',
            [
                'reusable' => true,
                'alias' => 'message',
                'params' => [
                    'conditions' => 'is_deleted = 0'
                ]
            ]
        );
    }
}
