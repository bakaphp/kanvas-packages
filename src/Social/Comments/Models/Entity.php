<?php
declare(strict_types=1);

namespace Kanvas\Packages\Social\Comments\Models;

use Kanvas\Packages\Social\Comments\Model;

class Entity extends Model
{
    public ?string $entity_id = null;
    public ?string $entity_namespace = null;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('entity_comments');
    }

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
                'reusable' => true,
                'alias' => 'entity',
                'params' => [
                    'conditions' => 'is_deleted = 0'
                ]
            ]
        );
    }
}
