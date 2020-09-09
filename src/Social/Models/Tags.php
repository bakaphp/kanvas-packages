<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Interactions\CustomTotalInteractionsTrait;
use Phalcon\Di;

class Tags extends BaseModel
{
    use CustomTotalInteractionsTrait;

    public $id;
    public int $apps_id;
    public int $companies_id;
    public int $users_id;
    public string $name;
    public string $slug;
    public int $weight = 0;
    public int $is_feature = 0;

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
    }

    /**
     * Verify if the user follow the tag.
     *
     * @return boolean
     */
    public function isFollow() : bool
    {
        return (bool) UsersFollows::count([
            'conditions' => 'users_id = :userId: AND entity_id = :entityId: AND entity_namespace = :entityName: AND is_deleted = 0',
            'bind' => [
                'userId' => Di::getDefault()->get('userData')->getId(),
                'entityId' => $this->getId(),
                'entityName' => get_class($this)
            ]
        ]);
    }
}
