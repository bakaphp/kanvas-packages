<?php

namespace Kanvas\Packages\Social\Models;

use Phalcon\Di;
use Phalcon\Mvc\Model\Resultset\Simple;

class ChannelMessages extends BaseModel
{
    public int $channel_id;
    public int $messages_id;
    public int $users_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        
        $this->setSource('channel_messages');
    }

    /**
    * Return all the messages of a channel
    *
    * @param Channels $channel
    * @param array $filter
    *
    * @return Simple
    */
    public function getMessagesByChannel(Channels $channel, int $page = 1, int $limit = 25, string $orderBy = "id", string $sort = "DESC"): Simple
    {
        $appData = Di::getDefault()->get('app');
        $userData = Di::getDefault()->get('userData');

        $offSet = ($page - 1) * $limit;

        $channelMessages = new Simple(
            null,
            new Messages(),
            Di::getDefault()->getShared('dbSocial')->query(
                "SELECT    * 
                FROM      channel_messages 
                LEFT JOIN messages 
                ON        messages.id = channel_messages.messages_id 
                WHERE     channel_messages.channel_id = {$channel->getId()} 
                AND       channel_messages.is_deleted = 0 
                AND       messages.apps_id = {$appData->getId()} 
                AND       messages.companies_id = {$userData->getCurrentCompany()->getId()}
                AND       messages.is_deleted = 0
                ORDER BY  {$orderBy} {$sort} limit {$limit} offset {$offSet}"
            )
        );

        return $channelMessages;
    }
}
