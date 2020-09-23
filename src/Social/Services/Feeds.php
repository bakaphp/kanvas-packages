<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\Channels;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\Social\Models\UserMessages;
use Phalcon\Mvc\Model\Resultset\Simple;

class Feeds
{

    /**
     * Return a Message object by its id
     *
     * @param string $uuid
     * @return Messages
     */
    public static function getMessage(string $uuid): Messages
    {
        $message = Messages::getByIdOrFail($uuid);
        
        return $message;
    }

    /**
     * Get the feeds of the user
     *
     * @param UserInterface $user
     * @param integer $limit
     * @param integer $page
     * @return Simple
     */
    public static function getByUser(UserInterface $user, int $page = 1, int $limit = 25): Simple
    {
        $feed = new UserMessages();
        return $feed->getUserFeeds($user, $limit, $page);
    }

    /**
     * Get the feeds of the channel
     *
     * @param Channels $user
     * @return Simple
     */
    public static function getByChannel(Channels $channel): Simple
    {
        return $channel->getMessages();
    }
}
