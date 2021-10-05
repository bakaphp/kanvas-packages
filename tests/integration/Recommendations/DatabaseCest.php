<?php
declare(strict_types=1);

namespace Kanvas\Packages\Tests\Integration\ZRecommendations;

use IntegrationTester;
use Kanvas\Packages\Recommendations\Drivers\Recombee\Engine;
use Kanvas\Packages\Test\Support\Recommendations\Database\Books;
use Kanvas\Packages\Test\Support\Recommendations\Database\Topics;
use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;

class DatabaseCest
{
    public function create(IntegrationTester $I) : void
    {
        $topics = new Topics();
        $engine = Engine::getInstance($topics);

        $createTable = $topics->create(
            $engine,
            function (Client $client) : Client {
                $client->send(new Reqs\AddItemProperty('created_at', 'timestamp'));
                $client->send(new Reqs\AddItemProperty('name', 'string'));
                $client->send(new Reqs\AddItemProperty('slug', 'string'));
                $client->send(new Reqs\AddItemProperty('users_id', 'int'));

                return $client;
            }
        );

        $I->assertTrue($createTable);
    }

    public function createFromAnotherType(IntegrationTester $I) : void
    {
        $books = new Books();
        $engine = Engine::getInstance($books);

        $createTable = $books->create(
            $engine,
            function (Client $client) : Client {
                $client->send(new Reqs\AddItemProperty('books_names', 'string'));

                return $client;
            }
        );

        $I->assertTrue($createTable);
    }
}
