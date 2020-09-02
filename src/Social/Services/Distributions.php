<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Models\ChannelMessages;
use Kanvas\Packages\Social\Models\Channels;
use Kanvas\Packages\Social\Models\Messages;

class Distributions
{

    /**
     * Send Messages to channels feeds
     *
     * @param string $channelName
     * @return ChannelsModel
     */
    public static function sendToChannelFeed(Channels $channel, Messages $message): ChannelMessages
    {
        $channelMessage = new ChannelMessages();
        $channelMessage->messages_id = $message->getId();
        $channelMessage->users_id = $message->users_id;
        $channelMessage->channel_id = $channel->getId();
        $channelMessage->saveOrFail();
        
        $channel->last_message_id = $message->getId();
        $channel->saveOrFail();

        return $channelMessage;
    }

    public static function sendToUsersFeeds(string $channelName)
    {
    }
}
