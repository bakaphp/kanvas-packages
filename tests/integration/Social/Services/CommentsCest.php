<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Services\Comments;
use Kanvas\Packages\Social\Models\Messages;
use Phalcon\Di;

class CommentsCest
{
    /**
     * @var \IntegrationTester
     */
    protected $tester;
    
    protected function _before()
    {
        Di::getDefault()->setShared('userData', $this->toUser);
    }

    /**
     * Test to get HashtagFromStrings
     *
     * @param UnitTester $I
     * @return void
     */
    public function addComment(IntegrationTester $I): void
    {
    }
}
