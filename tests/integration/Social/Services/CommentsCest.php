<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Services\Comments;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\Tests\Support\Models\Users;
use Phalcon\Di;

class CommentsCest
{
    /**
     * @var \IntegrationTester
     */
    // public $user;
    
    // protected function _before()
    // {
    //     $this->user = new Users();
    //     dd($this->user);
    //     $this->user = Di::getDefault()->setShared('userData', new Users());
    // }

    /**
     * Test to get HashtagFromStrings
     *
     * @param UnitTester $I
     * @return void
     */
    public function addComment(IntegrationTester $I): void
    {
        $this->user = Di::getDefault()->setShared('userData', new Users());
        dd($this->user);
        $user = new Users();
        dd($user->getId());
    }
}
