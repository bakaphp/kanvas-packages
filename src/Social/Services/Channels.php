<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Contract\Channels\ChannelsInterface;
use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\Channels as ChannelsModel;
use Kanvas\Packages\Social\Models\ChannelUsers;
use Phalcon\Utils\Slug;

class Channels
{

    /**
     * Get Channel by its name
     *
     * @param string $channelName
     * @return ChannelsModel
     */
    public static function getChannelByName(string $channelName): ChannelsModel
    {
        $channel = ChannelsModel::findFirstOrFail([
            'conditions' => 'slug = :slug: AND is_deleted = 0',
            'bind' => [
                'slug' => Slug::generate($channelName)
            ]
        ]);

        return $channel;
    }

    /**
     * Create a new Channel and assign his creator a rol
     *
     * @param UserInterface $user
     * @param string $name
     * @param string $description
     * @return ChannelsModel
     */
    public static function create(UserInterface $user, ChannelsInterface $channel_entity, string $name, string $description = ''): ChannelsModel
    {
        $channel = new ChannelsModel();
        $channel->name = $name;
        $channel->slug = Slug::generate($name);
        $channel->description = $description;
        $channel->entity_namespace = get_class($channel_entity);
        $channel->entity_id = (string) $channel_entity->getId();
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
