<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Models\Channels;
use Kanvas\Packages\Social\Models\Interactions as ModelsInteractions;
use Kanvas\Packages\Social\Services\Messages as MessagesService;
use Kanvas\Packages\Social\Services\MessageTypes;
use Kanvas\Packages\Social\Services\Reactions;
use Kanvas\Packages\Test\Support\Models\Users;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\Social\Services\Distributions;
use Kanvas\Packages\Social\Services\Interactions;
use Kanvas\Packages\Test\Support\Models\Lead;
use Kanvas\Packages\Test\Support\Models\MessageObject;

class MessagesCest
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

        $this->channel = Channels::findFirstOrCreate(
            [
            'conditions' => 'is_deleted = 0'
        ],
            [
            'name' => 'channel Test',
            'entity_namespace' => new Lead(),
            'entity_id' => $lead->getId()
        ]
        );

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
        
        $feed = MessagesService::create(new Users(), 'memo', $text, new MessageObject());

        $I->assertNotNull($feed->getId());
    }

    /**
     * Create Message by Message Object Test
     *
     * @param UnitTester $I
     * @before createMessageType
     * @return void
     */
    public function createMessageByObject(IntegrationTester $I): void
    {
        $text = [
            'text' => 'This is test text for testing'
        ];

        $newMessage =  new Messages();
        $newMessage->message = $text;
        
        $feed = MessagesService::createByObject(new Users(), 'memo', $newMessage, new Messages());

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
        $I->assertFalse(Reactions::addMessageReaction('confuse', new Users(), $this->message));

        $I->assertFalse(Reactions::addMessageReaction('☺', new Users(), $this->message));

        $I->assertTrue(Reactions::addMessageReaction('confuse', new Users(), $this->message));

        $I->assertTrue(Reactions::addMessageReaction('☺', new Users(), $this->message));
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
     * Test message and feed delete
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function deleteMessage(IntegrationTester $I): void
    {
        $I->assertTrue(
            MessagesService::delete(Messages::findFirst()->getId(), new Users())
        );
    }
}
