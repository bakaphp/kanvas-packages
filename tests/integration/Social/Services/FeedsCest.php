<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Models\Channels;
use Kanvas\Packages\Social\Services\Messages as MessagesService;
use Kanvas\Packages\Social\Services\MessageTypes;
use Kanvas\Packages\Test\Support\Models\Users;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\Social\Services\Distributions;
use Kanvas\Packages\Test\Support\Models\Lead;

class FeedsCest
{
    public Messages $message;
    public Channels $channel;

    /**
     * Get the first comment
     *
     * @return void
     */
    protected function createMessageType(): void
    {
        MessageTypes::create(new Users(), 'memo', 'Test Type');
    }

    /**
     * Create a message for testing
     *
     * @return void
     */
    protected function messageTestCreation(): void
    {
        $text = [
            'text' => 'Test some messages'
        ];
        
        $this->message = MessagesService::create(new Users(), 'memo', $text);
    }

    /**
     * Create a channel messages for testing
     *
     * @return void
     */
    protected function channelMessagesDistribution(): void
    {
        $lead = new Lead();

        $this->channel = Channels::findFirstOrCreate([
            'conditions' => 'is_deleted = 0'
        ],
        [
            'name' => 'channel Test',
            'entity_namespace' => new Lead(),
            'entity_id' => $lead->getId()
        ]);

        Distributions::sendToChannelFeed($this->channel, $this->message);

    }

    /**
     * Test get feeds by channel
     *
     * @param IntegrationTester $I
     * @before messageTestCreation
     * @before channelMessagesDistribution
     * @return void
     */
    public function getFeedsByChannel(IntegrationTester $I): void
    {
        
    }
}
