<?php
namespace Kanvas\Packages\Tests\unit\social\Utils;

use Kanvas\Packages\Social\Utils\StringFormatter;
use UnitTester;

class StringFormatterCest
{
    public function getHashtagToString(UnitTester $I)
    {
        $text = "Test with #tags to #test";

        $text->assertContains('tag', StringFormatter::getHashtagToString($text));
    }
}
