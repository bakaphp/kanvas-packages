<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Contract\Interfaces\UserInterface;

class Feeds
{
    /**
     * To be describe
     *
     * @param UserInterface $user
     * @param string $verb
     * @param array $message
     * @param array $object contains the entity object + its id.
     * @param string $distribution
     * @return void
     */
    public static function create(UserInterface $user, string $verb, array $message = [], array $object, string $distribution = 'profile')
    {
    }

    /**
     * To be describe
     *
     * @param string $uuid
     * @param array $message
     * @return void
     */
    public static function update(string $uuid, array $message)
    {
    }

    /**
     * To be describe
     *
     * @param string $uuid
     * @return void
     */
    public static function delete(string $uuid)
    {
    }

    /**
     * To be describe
     *
     * @param string $message
     * @return void
     */
    public static function comment(string $message)
    {
    }
}
