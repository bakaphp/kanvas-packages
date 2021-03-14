<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Services\Follow;
use Kanvas\Packages\Test\Support\Models\Tag;
use Kanvas\Packages\Test\Support\Models\Users;

class FollowsCest
{
    /**
     * Create a two follows tags for testing.
     *
     * @return void
     */
    protected function testFollow() : void
    {
        for ($i = 0; $i < 10; $i++) {
            $tag1 = new Tag();
            $tag1->id = $i;

            Follow::userFollow(new Users(), $tag1);
        }
    }

    /**
     * Test follow service method user follow.
     *
     * @param IntegrationTester $I
     *
     * @return void
     */
    public function follow(IntegrationTester $I) : void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new Users();
            $user->id = $i;

            $userFollow = new Users();
            $userFollow->id = $i;

            $follow = Follow::userFollow($user, $userFollow);

            $I->assertFalse($follow);
        }
    }

    /**
     * Test Users follows by entities.
     *
     * @param IntegrationTester $I
     * @before testFollow
     *
     * @return void
     */
    public function getUserFollows(IntegrationTester $I) : void
    {
        $user = new Users();
        $user->id = 1;

        $tag1 = new Tag();
        $tag1->id = 1;
        $follows = Follow::getFollowsByUser($user, $tag1)->toArray();
        $I->assertNotNull($follows[0]['id']);

        $I->assertEquals($follows[0]['entity_namespace'], get_class(new Tag()));
    }
}
