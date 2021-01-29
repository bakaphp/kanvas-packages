<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Models\ChannelMessages;
use Kanvas\Packages\Social\Models\Channels as ChannelsModel;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\Social\Services\Distributions;
use Kanvas\Packages\Test\Support\Models\App;
use Kanvas\Packages\Test\Support\Models\Users;
use Phalcon\Security\Random;

class DistributionCest
{
    public ChannelsModel $channel;
    public Messages $message;

    /**
     * Get the first channel
     *
     * @return void
     */
    protected function setDistributionsParams(): void
    {
        $random = new Random();
        $this->channel = ChannelsModel::findFirstOrCreate([
            'conditions' => 'is_deleted = 0'
        ],
        [
            'name' => 'Channel-Test',
        ]);

        $user = new Users();
        $app = new App();

        $this->message = Messages::findFirstOrCreate([
            'conditions' => 'is_deleted = 0'
        ],[
            'uuid' => $random->uuid();
            'apps_id' => $app->getId(),
            'companies_id' => $user->getDefaultCompany(),
            'users_id' => $user->getId(),
            'message_types_id' => 1,
            'message' => "Test for distribution",
        ]);
    }
    
    /**
     * Test distribution of message to channel feed
     *
     * @param IntegrationTester $I
     * @before setDistributionsParams
     * @return void
     */
    public function sendMessageToChannelFeed(IntegrationTester $I): void
    {
        $channelFeeds = Distributions::sendToChannelFeed($this->channel, $this->message);

        $I->assertInstanceOf(ChannelMessages::class, $channelFeeds);
        $I->assertEquals($channelFeeds->channel_id, $this->channel->getId());
        $I->assertEquals($channelFeeds->messages_id, $this->message->getId());
    }
}
