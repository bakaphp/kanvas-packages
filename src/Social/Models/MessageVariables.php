<?php

namespace Kanvas\Packages\Social\Models;

class MessageVariables extends BaseModel
{
    public $id;
    public $message_id;
    public $key;
    public $value;

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
                'alias' => 'message',
                'params' => [
                    'conditions' => 'is_deleted = 0'
                ]
            ]
        );
    }
}
