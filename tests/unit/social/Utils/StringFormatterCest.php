<?php

namespace Kanvas\Packages\Tests\Unit\Social\Utils;

use Kanvas\Packages\Social\Utils\StringFormatter;
use UnitTester;

class StringFormatterCest
{
    /**
     * Test to get HashtagFromStrings
     *
     * @param UnitTester $I
     * @return void
     */
    public function getHashtagToString(UnitTester $I): void
    {
        $text = "Test with #tags to #test";

        $I->assertContains('tags', StringFormatter::getHashtagToString($text));
        $I->assertContains('test', StringFormatter::getHashtagToString($text));
    }

    /**
     * Test if the string is an emoji
     *
     * @param UnitTester $I
     * @return void
     */
    public function isStringEmoji(UnitTester $I): void
    {
        $emoji = "🙄";
        $text = "No emoji";

        $I->assertTrue(StringFormatter::isStringEmoji($emoji));
        $I->assertFalse(StringFormatter::isStringEmoji($text));
    }

    /**
     *
     *
     * @param UnitTester $I
     * @return void
     */
    public function getEmojiFromString(UnitTester $I): void
    {
        $text = "Text with emoji 😛";

        $I->assertContains('😛', StringFormatter::getEmojiFromString($text));
    }
}
