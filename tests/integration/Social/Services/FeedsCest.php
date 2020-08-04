<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use Codeception\Lib\Di;
use IntegrationTester;
use Kanvas\Packages\Social\Services\Feeds;
use Kanvas\Packages\Social\Services\MessageTypes;
use Kanvas\Packages\Test\Support\Models\Users;

class FeedsCest
{
    public $comment;

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
     * Test add comment
     *
     * @param UnitTester $I
     * @before createMessageType
     * @return void
     */
    public function createMessage(IntegrationTester $I): void
    {
        $text = [
            'text' => 'This is test text'
        ];
        
        $feed = Feeds::create(new Users(), 'memo', $text);

        $I->assertNotNull($feed->getId());
    }
}
