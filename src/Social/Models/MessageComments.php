<?php

namespace Kanvas\Packages\Social\Models;

class MessageComments extends BaseModel
{
    public $id;
    public $message_id;
    public $apps_id;
    public $companies_id;
    public $users_id;
    public $message;
    public $reactions_count;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('message_comments');

        $this->hasMany(
            'id',
            UsersInteractions::class,
            'entity_id',
            [
                'alias' => 'interactions',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace:',
                    'bind' => [
                        'namespace' => get_class($this)
                    ]
                ]
            ]
        );

        $this->hasOne(
            'id',
            UsersInteractions::class,
            'entity_id',
            [
                'alias' => 'interaction',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace:',
                    'bind' => [
                        'namespace' => get_class($this)
                    ]
                ]
            ]
        );

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
    
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'message_comments';
    }
}
