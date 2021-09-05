<?php
declare(strict_types=1);

namespace Kanvas\Packages\Tests\Integration\ZRecommendations;

use IntegrationTester;
use Kanvas\Packages\Recommendations\Drivers\Recombee\Engine;
use Kanvas\Packages\Test\Support\Recommendations\Database\Topics;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;

class ZDatabaseDeleteCest
{
    public function delete(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);

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

        $I->assertTrue($delete);
    }
}
