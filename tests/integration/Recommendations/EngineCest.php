<?php
declare(strict_types=1);

namespace Kanvas\Packages\Tests\Integration\Payments\Plaid;

use IntegrationTester;
use Kanvas\Packages\Recommendations\Contracts\Interactions;
use Kanvas\Packages\Recommendations\Contracts\Items;
use Kanvas\Packages\Recommendations\Contracts\Recommendation;
use Kanvas\Packages\Recommendations\Drivers\Recombee\Engine;
use Kanvas\Packages\Test\Support\Recommendations\Database\Topics;
use Recombee\RecommApi\Client;

class EngineCest
{
    public function getInstance(IntegrationTester $I) : void
    {
        $engine = Engine::getInstance(new Topics());

        $I->assertIsObject($engine);
        $I->assertTrue($engine instanceof Engine);
        $I->assertTrue($engine->connect() instanceof Client);
    }

    public function connection(IntegrationTester $I) : void
    {
        $engine = Engine::getInstance(new Topics());

        $I->assertIsObject($engine);
        $I->assertTrue($engine->connect() instanceof Client);
    }

    public function interactions(IntegrationTester $I) : void
    {
        $engine = Engine::getInstance(new Topics());

        $I->assertIsObject($engine);
        $I->assertTrue($engine->interactions() instanceof Interactions);
    }

    public function items(IntegrationTester $I) : void
    {
        $engine = Engine::getInstance(new Topics());

        $I->assertIsObject($engine);
        $I->assertTrue($engine->items() instanceof Items);
    }

    public function recommendation(IntegrationTester $I) : void
    {
        $engine = Engine::getInstance(new Topics());

        $I->assertIsObject($engine);
        $I->assertTrue($engine->recommendation() instanceof Recommendation);
    }
}
