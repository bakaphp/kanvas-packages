<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Utils;

/**
 * New event Manager to allow use to use fireToQueue.
 */
class StringFormatter
{
    /**
     * Return an array of words that have hashtags
     *
     * @param string $text
     * @return array
     */
    public static function getHashtagToString(string $text): array
    {
        preg_match_all('/#(\w+)/', $text, $hashTag);

        return $hashTag[1];
    }
}
