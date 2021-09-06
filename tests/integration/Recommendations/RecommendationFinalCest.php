<?php
declare(strict_types=1);

namespace Kanvas\Packages\Tests\Integration\ZRecommendations;

use IntegrationTester;
use Kanvas\Packages\Recommendations\Drivers\Recombee\Engine;
use Kanvas\Packages\Recommendations\Recommendation;
use Kanvas\Packages\Social\Models\Tags;
use Kanvas\Packages\Test\Support\Models\Users;
use Kanvas\Packages\Test\Support\Recommendations\Database\Topics;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;

class RecommendationFinalCest
{
    public function use(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $user = Users::findFirst();

        $recommendation = new Recommendation($engine);

        $I->assertIsObject($recommendation->interactions());
        $I->assertIsObject($recommendation->items());
        $I->assertIsObject($recommendation->get());
    }

    public function createDatabase(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $user = Users::findFirst();

        $recommendation = new Recommendation($engine);

        $delete = $topics->delete(
            $engine,
            function (Client $client) : Client {
                $client->send(new Reqs\DeleteItemProperty('created_at', 'timestamp'));
                $client->send(new Reqs\DeleteItemProperty('name', 'string'));
                $client->send(new Reqs\DeleteItemProperty('slug', 'string'));
                $client->send(new Reqs\DeleteItemProperty('users_id', 'int'));

                return $client;
            }
        );

        $createTable = $recommendation->create(
            function (Client $client) : Client {
                $client->send(new Reqs\AddItemProperty('created_at', 'timestamp'));
                $client->send(new Reqs\AddItemProperty('name', 'string'));
                $client->send(new Reqs\AddItemProperty('slug', 'string'));
                $client->send(new Reqs\AddItemProperty('users_id', 'int'));

                return $client;
            }
        );

        $I->assertTrue($delete);
        $I->assertTrue($createTable);
    }

    public function listItems(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);


        $recommendation = new Recommendation($engine);
        $listItems = $recommendation->items()->list([]);

        $I->assertIsArray($listItems);
    }

    public function like(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);

        $tags = Tags::findFirst();
        $user = Users::findFirst();
        $recommendation = new Recommendation($engine);

        $like = $recommendation->interactions()->likes($user, $tags);

        $I->assertTrue($like);
    }

    public function itemsToUser(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);
        $user = Users::findFirst();

        $recommendation = new Recommendation($engine);

        $list = $recommendation->get()->itemsToUser($user, 10);

        $I->assertIsArray($list);
        $I->assertArrayHasKey('recomms', $list);
    }
}
