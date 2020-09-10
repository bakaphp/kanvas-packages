<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Di;

class UserMessages extends BaseModel
{
    public int $messages_id;
    public int $users_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('user_messages');
    }

    /**
     * Return all the messages that the user have in its feed
     *
     * @param UserInterface $user
     * @return Simple
     */
    public function getUserFeeds(UserInterface $user): Simple
    {
       $appData = Di::getDefault()->get('app');

       $userFeeds = new Simple(
            null,
            new Messages(),
            $this->getReadConnection()->query(
            "SELECT * from messages where id in (SELECT messages_id from user_messages where users_id = {$user->getId()} and is_deleted = 0) and apps_id = {$appData->getId()}"
            )
        );

        return $userFeeds;
    }
}
