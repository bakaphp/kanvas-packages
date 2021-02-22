<?php

namespace Kanvas\Packages\Tests\Integration\Social\Contracts;

use IntegrationTester;
use Kanvas\Packages\Social\Models\Channels as ChannelsModel;
use Kanvas\Packages\Social\Models\ChannelUsers;
use Kanvas\Packages\Social\Services\Channels;
use Kanvas\Packages\Test\Support\Models\Lead;
use Kanvas\Packages\Test\Support\Models\Users;

class ChannelsTraitCest
{
    public ChannelsModel $channel;

    public Lead $lead;

    /**
     * set objects.
     *
     * @return void
     */
    public function onConstruct() : void
    {
        $this->lead = new Lead();
    }

    /**
     * Get the first channel
     *
     * @return void
     */
    protected function getChannel(): void
    {
        $this->channel = ChannelsModel::findFirstOrCreate(
            [
            'conditions' => 'is_deleted = 0'
        ],
            [
            'name' => 'channel Test',
            'entity_namespace' => get_class($this->lead),
            'entity_id' => '0'
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
        $channel = $this->lead->createChannele(new Users(), 'Test', 'Channel for testing propose');

        $I->assertInstanceOf(ChannelsModel::class, $channel);
        $I->assertEquals(get_class($this->lead), $channel->entity_namespace);
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

        $newChannelUser = $this->lead::addUser($this->channel, $user);
        
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
        $channel = $this->lead->getChannelByName($this->channel->name);

        $I->assertInstanceOf(ChannelsModel::class, $channel);
        $I->assertEquals($this->channel->getId(), $channel->getId());
    }
}
