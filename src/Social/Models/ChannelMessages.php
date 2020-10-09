<?php

namespace Kanvas\Packages\Social\Models;

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
    * default $filter = ["page" => 1, "limit" => 25, "order_by" => "id", "sort" => "DESC"]
    *
    * @return Simple
    */
    public function getMessages(Channels $channel, array $filter = []): Simple
    {
        $appData = Di::getDefault()->get('app');
        $userData = Di::getDefault()->get('userData');

        if (empty($filter)) {
            $filter = [
                "page" => 1,
                "limit" => 25,
                "order_by" => "id",
                "sort" => "DESC"
            ];
        }

        $offSet = ($filter["page"] - 1) * $filter["limit"];

        $channelMessages = new Simple(
            null,
            new Messages(),
            Di::getDefault()->getShared('dbSocial')->query(
                "SELECT 
            * 
            from 
                channel_messages 
                left join 
                messages on messages.id = channel_messages.messages_id 
            where channel_messages.channel_id = {$channel->getId()}
            and channel_messages.is_deleted = 0 
            and messages.apps_id = {$appData->getId()}
            and messages.companies_id = {$userData->getCurrentCompany()->getId()}
            order by {$filter["order_by"]} {$filter['sort']}
            limit {$filter["limit"]} OFFSET {$offSet}"
            )
        );

        return $channelMessages;
    }
}
