<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contracts\Interactions\CustomTotalInteractionsTrait;

class Reactions extends BaseModel
{
    use CustomTotalInteractionsTrait;

    public string $name;
    public int $apps_id;
    public int $companies_id;
    public ?string $icon = null;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->hasMany(
            'id',
            UsersReactions::class,
            'reactions_id',
            [
                'params' => 'is_deleted = 0',
                'reusable' => true,
                'alias' => 'usersReactions'
            ]
        );
    }
}
