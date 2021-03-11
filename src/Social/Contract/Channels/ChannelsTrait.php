<?php

declare(strict_types=1);
namespace Kanvas\Packages\Social\Contract\Channels;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\Channels as ChannelsModel;
use Kanvas\Packages\Social\Models\ChannelUsers;
use Phalcon\Utils\Slug;

/**
 * Channels Trait
 */
trait ChannelsTrait
{
    /**
     * Get Channel by its name
     *
     * @param string $channelName
     * @return ChannelsModel
     */
    public function getChannelByName(string $channelName): ChannelsModel
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
    public function createChannel(UserInterface $user, string $name, string $description = ''): ChannelsModel
    {
        $channel = new ChannelsModel();
        $channel->name = $name;
        $channel->slug = Slug::generate($name);
        $channel->description = $description;
        $channel->entity_namespace = get_class($this);
        $channel->entity_id = (string) $this->getId();
        $channel->saveOrFail();

        self::addUser($channel, $user);

        return $channel;
    }

    /**
     * Add a new user to the channel
     *
     * @param ChannelsModel $channel
     * @param UserInterface $newUser
     * @param integer $rolId
     * @return ChannelUsers
     */
    public static function addUser(ChannelsModel $channel, UserInterface $newUser, int $rolId = 1): ChannelUsers
    {
        $channelUser = ChannelUsers::findFirstOrCreate(
            [
            'conditions' => 'users_id = :user_id: AND channel_id = :channel_id: AND is_deleted = 0',
            'bind' => [
                'user_id' => $newUser->getId(),
                'channel_id' => $channel->getId(),
                ]
            ],
            [
                'channel_id' => $channel->getId(),
                'users_id' => $newUser->getId(),
                'roles_id' => $rolId
            ]
        );

        return $channelUser;
    }
}
