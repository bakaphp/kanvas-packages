<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use Codeception\Lib\Di;
use IntegrationTester;
use Kanvas\Packages\Social\Services\Feeds;
use Kanvas\Packages\Social\Services\MessageTypes;
use Kanvas\Packages\Social\Services\Reactions;
use Kanvas\Packages\Test\Support\Models\Users;

class FeedsCest
{
    public $message;

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
        {
            $text = [
                'text' => 'Test some messages'
            ];
            
            $this->message = Feeds::create(new Users(), 'memo', $text);
        }
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
}
