<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\AppModuleMessage;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\Social\Models\UserMessages;
use Phalcon\Di;

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
     * @return UserMessages
     */
    public static function create(UserInterface $user, string $verb, array $message = [], array $object = null, string $distribution = 'profile'): Messages
    {
        $newMessage = new Messages();
        $newMessage->apps_id = Di::getDefault()->get('app')->getId();
        $newMessage->companies_id = $user->getDefaultCompany()->getId();
        $newMessage->users_id = $user->getId();
        $newMessage->message_types_id = MessageTypes::getTypeByVerb($verb);
        $newMessage->message = json_encode($message);
        $newMessage->saveOrFail();
        $newMessage->addDistributionChannel($distribution);

        $newAppModule = new AppModuleMessage();
        $newAppModule->message_id = $newMessage->getId();
        $newAppModule->message_types_id = $newMessage->message_types_id;
        $newAppModule->apps_id = $newMessage->apps_id; //Duplicate data?
        $newAppModule->companies_id = $newMessage->companies_id; //Duplicate data?
        $newAppModule->system_modules_id =  $object['entity_namespace'] ?? null;
        $newAppModule->entity_id =  $object['entity_id'] ?? null;
        $newAppModule->saveOrFail();

        return $newMessage;
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
}
