<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Phalcon\Mvc\Model\Resultset\Simple;

class UserMessages extends BaseModel
{
    public $messages_id;
    public $users_id;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'user_messages';
    }

    /**
     * Return all the messages that the user have in its feed
     *
     * @param UserInterface $user
     * @return Simple
     */
    public function getUserFeeds(UserInterface $user): Simple
    {
        $userFeeds = new Simple(
            null,
            new Messages(),
            $this->getReadConnection()->query(
                "SELECT * from messages where id in (SELECT messages_id from user_messages where users_id = {$user->getId()} and is_deleted = 0)"
            )
        );

        return $userFeeds;
    }
}
