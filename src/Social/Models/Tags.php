<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Interactions\TotalInteractionsTrait;

class Tags extends BaseModel
{
    use TotalInteractionsTrait {
        getInteractionStorageKey as protected parentGetInteractionStorageKey;
    }

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
    
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'tags';
    }

    /**
     * Get the interaction key.
     *
     * @return string
     */
    protected function getInteractionStorageKey(): string
    {
        if (!is_null($this->interaction_type_id)) {
            return $this->parentGetInteractionStorageKey() . '-' . $this->interaction_type_id;
        }
        return $this->parentGetInteractionStorageKey();
    }
}
