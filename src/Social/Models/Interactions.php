<?php

namespace Kanvas\Packages\Social\Models;

class Interactions extends BaseModel
{
    public $id;
    public $name;
    public $icon;

    const LIKE = 1;
    const SAVE = 2;
    const COMMENT = 3;
    const REPLIED = 4;
    const FOLLOWING = 5;
    const FOLLOWERS = 6;

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

    /**
     * Verify if the interacion is a comment/reply
     *
     * @param integer $interactionId
     * @return boolean
     */
    public static function isComment(int $interactionId): bool
    {
        return $interactionId == self::COMMENT && $interactionId == self::REPLIED;
    }
}
