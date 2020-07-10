<?php

namespace Kanvas\Packages\Tests\Unit\Social\Utils;

use Kanvas\Packages\Social\Utils\StringFormatter;
use UnitTester;

class StringFormatterCest
{
    public function getHashtagToString(UnitTester $I)
    {
        $text = "Test with #tags to #test";

        $I->assertContains('tag', StringFormatter::getHashtagToString($text));
    }
}
