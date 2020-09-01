<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Models\Channels;
use Kanvas\Packages\Social\Models\Interactions as ModelsInteractions;
use Kanvas\Packages\Social\Services\Feeds;
use Kanvas\Packages\Social\Services\MessageTypes;
use Kanvas\Packages\Social\Services\Reactions;
use Kanvas\Packages\Test\Support\Models\Users;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\Social\Services\Distributions;
use Kanvas\Packages\Social\Services\Interactions;
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
        
        $this->message = Feeds::create(new Users(), 'memo', $text);
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
     * Test add comment
     *
     * @param UnitTester $I
     * @before createMessageType
     * @return void
     */
    public function createMessage(IntegrationTester $I): void
    {
        $text = [
            'text' => 'This is test text for testing'
        ];
        
        $feed = Feeds::create(new Users(), 'memo', $text);

        $I->assertNotNull($feed->getId());
    }

    /**
     * Test Message Reactions
     *
     * @param IntegrationTester $I
     * @before messageTestCreation
     * @return void
     */
    public function messageReaction(IntegrationTester $I): void
    {
        $reaction = Reactions::addMessageReaction('confuse', new Users(), $this->message);

        $I->assertEquals($this->message->getId(), $reaction->entity_id);

        $reaction = Reactions::addMessageReaction('☺', new Users(), $this->message);
        $I->assertEquals($this->message->getId(), $reaction->entity_id);
    }

    /**
     * Test Message Interaction
     *
     * @param IntegrationTester $I
     * @before messageTestCreation
     * @return void
     */
    public function messageInteraction(IntegrationTester $I): void
    {
        $I->assertFalse(
            Interactions::add(new Users(), $this->message, ModelsInteractions::REACT)
        );
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

    /**
     * Test message and feed delete
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function deleteMessage(IntegrationTester $I): void
    {
        $I->assertTrue(
            Feeds::delete(Messages::findFirst()->getId(),new Users())
        );
    }
}
