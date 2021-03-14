<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Utils;

/**
 * New event Manager to allow use to use fireToQueue.
 */
class StringFormatter
{
    /**
     * String that contain regex to check emoji.
     *
     * @var string
     */
    const EMOJIREGEX = '/([0-9#][\x{20E3}])|[\x{00ae}\x{00a9}\x{203C}\x{2047}\x{2048}\x{2049}\x{3030}\x{303D}\x{2139}\x{2122}\x{3297}\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u';

    /**
     * Return an array of words that have hashtags.
     *
     * @param string $text
     *
     * @return array
     */
    public static function getHashtagToString(string $text) : array
    {
        preg_match_all('/#(\w+)/', $text, $hashTag);

        return $hashTag[1];
    }

    /**
     * Verify if the string if or have an emoji.
     *
     * @param string $emoji
     *
     * @return bool
     */
    public static function isStringEmoji(string $emoji) : bool
    {
        return (bool) preg_match_all(
            self::EMOJIREGEX,
            $emoji
        );
    }

    /**
     * Return an array of emojis inside the text.
     *
     * @param string $text
     *
     * @return array|bool
     */
    public static function getEmojiFromString(string $text)
    {
        if (preg_match_all(self::EMOJIREGEX, $text, $emojis)) {
            return $emojis[0];
        }

        return false;
    }
}
