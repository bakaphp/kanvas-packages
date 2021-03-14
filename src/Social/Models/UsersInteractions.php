<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contracts\Interactions\TotalInteractionsTrait;

class UsersInteractions extends BaseModel
{
    use TotalInteractionsTrait {
        getInteractionStorageKey as protected parentGetInteractionStorageKey;
    }

    public int $users_id;
    public int $entity_id;
    public string $entity_namespace;
    public int $interactions_id;

    const LIKE = 'like';
    const SAVE = 'save';
    const COMMENT = 'comment';
    const REPLIED = 'reply';
    const FOLLOWING = 'follow';

    /**
     * Initialize relationship after fetch
     * since we need entity_namespace info.
     *
     * @return void
     */
    public function afterFetch()
    {
        $this->hasOne(
            'entity_id',
            $this->entity_namespace,
            'id',
            [
                'alias' => 'entityData',
                'params' => [
                    'conditions' => 'is_deleted = 0'
                ]
            ]
        );
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        
        $this->setSource('users_interactions');
    }

    /**
     * Get the interaction key.
     *
     * @return string
     */
    protected function getInteractionStorageKey(): string
    {
        return $this->entity_namespace . '-' . $this->entity_id .'-' . $this->interactions_id;
    }
}
