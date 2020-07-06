<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Interactions\CustomTotalInteractionsTrait;

class Tags extends BaseModel
{
    use CustomTotalInteractionsTrait;

    public $id;
    public $apps_id;
    public $companies_id;
    public $users_id;
    public $name;
    public $slug;
    public $weight;
    public $is_feature;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('tags');

        $this->hasManyToMany(
            'id',
            MessageTags::class,
            'tags_id',
            'message_id',
            Messages::class,
            'id',
            [
                'alias' => 'messages'
            ]
        );

        $this->hasMany(
            'id',
            UsersFollows::class,
            'entity_id',
            [
                'alias' => 'follows',
                'params' => 'is_deleted = 0'
            ]
        );

        $this->hasOne(
            'id',
            UsersFollows::class,
            'entity_id',
            [
                'alias' => 'follow',
                'params' => [
                    'conditions' => 'entity_namespace = :namespace:',
                    'bind' => [
                        'namespace' => get_class($this)
                    ]
                ]
            ]
        );
    }
}
