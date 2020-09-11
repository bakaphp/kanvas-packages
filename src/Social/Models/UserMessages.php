<?php

namespace Kanvas\Packages\Social\Models;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Di;
use Phalcon\Paginator\Adapter\Model;
use Phalcon\Paginator\Adapter\QueryBuilder;

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
    * @param integer $limit
    * @param integer $page
    * @return Simple
    */
    public function getUserFeeds(UserInterface $user, int $limit = 25, int $page = 1): Simple
    {
       $appData = Di::getDefault()->get('app');

       $offSet = ($page - 1) * $limit;

       $userFeeds = new Simple(
            null,
            new Messages(),
            $this->getReadConnection()->query(
            "SELECT 
            * 
            from 
                user_messages 
                left join 
                messages on messages.id = user_messages.messages_id 
            where user_messages.users_id = {$user->getId()}
            and user_messages.is_deleted = 0 
            and messages.apps_id = {$appData->getId()}
            limit {$limit} OFFSET {$offSet}")
        );

        return $userFeeds;
    }
}
