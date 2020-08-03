<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use Codeception\Lib\Di;
use IntegrationTester;
use Kanvas\Packages\Social\Models\MessageComments;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\Social\Services\Comments;
use Kanvas\Packages\Social\Services\Feeds;
use Kanvas\Packages\Test\Support\Models\Users;

class FeedsCest
{
    public $comment;

    /**
     * Get the first comment
     *
     * @return void
     */
    protected function getCommentData(): void
    {
        $this->comment = MessageComments::findFirst();
    }
    
    /**
     * Test add comment
     *
     * @param UnitTester $I
     * @return void
     */
    public function createMessage(IntegrationTester $I): void
    {
        $text = [
            'text' => 'This is test text'
        ];
        
        $feed = Feeds::create(new Users(), 'memo', $text);
        dd($feed);
        $I->assertNotNull($feed->getId());
    }
}
