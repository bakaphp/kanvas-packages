<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\Channels as ChannelsModel;
use Kanvas\Packages\Social\Models\ChannelUsers;

class Channels
{
    /**
     * Create a new Channel and assign his creator a rol
     *
     * @param UserInterface $user
     * @param string $name
     * @param string $description
     * @return ChannelsModel
     */
    public static function create(UserInterface $user, string $name, string $description = ''): ChannelsModel
    {
        $channel = new ChannelsModel();
        $channel->name = $name;
        $channel->description = $description;
        $channel->saveOrFail();

        self::addUser($channel->getId(), $user);

        return $channel;
    }

    /**
     * Add a new user to the channel
     *
     * @param integer $channelId
     * @param UserInterface $newUser
     * @param integer $rolId
     * @return ChannelUsers
     */
    public static function addUser(int $channelId, UserInterface $newUser, int $rolId = 1): ChannelUsers
    {
        $channelUser = ChannelUsers::findFirstOrCreate(
            [
            'conditions' => 'users_id = :user_id: AND channel_id = :channel_id: AND is_deleted = 0',
            'bind' => [
                'user_id' => $newUser->getId(),
                'channel_id' => $channelId,
                ]
            ],
            [
                'channel_id' => $channelId,
                'users_id' => $newUser->getId(),
                'roles_id' => $rolId
            ]
        );

        return $channelUser;
    }
}
