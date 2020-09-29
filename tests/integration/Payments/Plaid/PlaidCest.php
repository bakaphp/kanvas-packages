<?php

namespace Kanvas\Packages\Tests\Integration\Payments\Plaid;

use TomorrowIdeas\Plaid\Plaid;

class PlaidCest
{
    public function checkProvider(IntegrationTester $I) : void
    {
        $plaid = $I->grabFromDi('plaid');
        $I->assertTrue($plaid instanceof Plaid);
    }
}
