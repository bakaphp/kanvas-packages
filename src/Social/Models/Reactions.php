<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Interactions\CustomTotalInteractionsTrait;

class Reactions extends BaseModel
{
    use CustomTotalInteractionsTrait;

    public $id;
    public $name;
    public $apps_id;
    public $companies_id;
    public $icon;
    public $is_deleted;
    public $is_feature;

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
                'alias' => 'usersReactions'
            ]
        );
    }
}
