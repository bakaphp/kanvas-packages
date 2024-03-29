<?php
declare(strict_types=1);

namespace Kanvas\Packages\Tests\Integration\Payments\Plaid;

use IntegrationTester;
use TomorrowIdeas\Plaid\Plaid;

class PlaidCest
{
    public function checkProvider(IntegrationTester $I) : void
    {
        $plaid = $I->grabFromDi('plaid');
        $I->assertTrue($plaid instanceof Plaid);
    }
}
