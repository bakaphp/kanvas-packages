<?php

namespace Kanvas\Packages\Social\Models;

class Interactions extends BaseModel
{
    public $id;
    public $name;
    public $title;
    public $icon;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('interactions');

        $this->hasMany(
            'id',
            UsersInteractions::class,
            'interactions_id',
            [
                'alias' => 'usersInteractions'
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
        return 'interactions';
    }
}
