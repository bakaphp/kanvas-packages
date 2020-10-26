<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Services\Follow;
use Kanvas\Packages\Test\Support\Models\Tag;
use Kanvas\Packages\Test\Support\Models\Users;

class FollowsCest
{
    /**
     * Create a two follows tags for testing
     *
     * @return void
     */
    protected function testFollow(): void
    {
        $tag1 = new Tag();
        $tag1->id = 1;

        $tag2 = new Tag();
        $tag2->id = 2;
        
        Follow::userFollow(new Users(), $tag1);
        Follow::userFollow(new Users(), $tag2);
    }


    /**
     * Test follow service method user follow
     *
     * @param IntegrationTester $I
     * @return void
     */
    public function follow(IntegrationTester $I): void
    {
        $user = new Users();
        $userFollow = new Users();
        $userFollow->id = 2;

        $follow = Follow::userFollow($user, $userFollow);

        $I->assertFalse($follow);
    }

    /**
     * Test Users follows by entitie
     *
     * @param IntegrationTester $I
     * @before testFollow
     * @return void
     */
    public function getUserFollows(IntegrationTester $I): void
    {
        $follows = Follow::getFollowsByUser(new Users(), new Tag())->toArray();

        $I->assertNotNull($follows[0]['id']);

        $I->assertEquals($follows[0]['entity_namespace'], get_class(new Tag()));
    }
}
