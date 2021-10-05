<?php
declare(strict_types=1);

namespace Kanvas\Packages\Tests\Integration\ZRecommendations;

use IntegrationTester;
use Kanvas\Packages\Recommendations\Drivers\Recombee\Engine;
use Kanvas\Packages\Recommendations\Drivers\Recombee\Recommendation;
use Kanvas\Packages\Social\Models\Tags;
use Kanvas\Packages\Test\Support\Models\Users;
use Kanvas\Packages\Test\Support\Recommendations\Database\Topics;

class RecommendationCest
{
    public function itemsToUser(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $user = Users::findFirst();

        $recommendation = new Recommendation($engine);

        $list = $recommendation->itemsToUser($user, 10);

        $I->assertIsArray($list);
        $I->assertArrayHasKey('recomms', $list);
    }

    public function itemsToItems(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $user = Users::findFirst();
        $tags = Tags::findFirst();

        $recommendation = new Recommendation($engine);

        $list = $recommendation->itemsToItems($tags, $user, 10, []);

        $I->assertIsArray($list);
        $I->assertArrayHasKey('recomms', $list);
    }

    public function userToUser(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $user = Users::findFirst();
        $tags = Tags::findFirst();

        $recommendation = new Recommendation($engine);

        $list = $recommendation->userToUser($user, 10, []);


        $I->assertIsArray($list);
        $I->assertArrayHasKey('recomms', $list);
    }
}
