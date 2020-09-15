<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Models\Channels as ChannelsModel;
use Kanvas\Packages\Social\Models\ChannelUsers;
use Kanvas\Packages\Social\Services\Channels;
use Kanvas\Packages\Test\Support\Models\Lead;
use Kanvas\Packages\Test\Support\Models\Users;

class ChannelsCest
{
    public ChannelsModel $channel;

    /**
     * Get the first channel
     *
     * @return void
     */
    protected function getChannel(): void
    {
        $lead = new Lead();

        $this->channel = ChannelsModel::findFirstOrCreate([
            'conditions' => 'is_deleted = 0'
        ],
        [
            'name' => 'channel Test',
            'entity_namespace' => new Lead(),
            'entity_id' => $lead->getId()
        ]);
    }
    
    /**
     * Test channel creation
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function createChannel(IntegrationTester $I): void
    {
        $channel = Channels::create(new Users(), new Lead(),'Test', 'Channel for testing propose');

        $I->assertInstanceOf(ChannelsModel::class, $channel);
        $I->assertEquals(get_class(new Lead()), $channel->entity_namespace);
        $I->assertEquals('Test', $channel->name);
    }

    /**
     * Test the add of a user to a channel
     *
     * @param IntegrationTester $I
     * @before getChannel
     * @return void
     */
    public function addUserToChannel(IntegrationTester $I): void
    {
        $user = new Users();
        $user->id = 2;

        $newChannelUser = Channels::addUser($this->channel, $user);
        
        $I->assertInstanceOf(ChannelUsers::class, $newChannelUser);
        $I->assertEquals($user->id, $newChannelUser->users_id);
    }

    /**
     * Test get channel data by its name
     *
     * @param IntegrationTester $I
     * @before getChannel
     * @return void
     */
    public function getChannelByItsName(IntegrationTester $I): void
    {
        $channel = Channels::getChannelByName($this->channel->name);

        $I->assertInstanceOf(ChannelsModel::class, $channel);
        $I->assertEquals($this->channel->getId(), $channel->getId());
    }
}
