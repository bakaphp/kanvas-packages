<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\MessageTypes as MessageTypesModel;
use Phalcon\Di;

class MessageTypes
{
    /**
     * Create a new MessageTypes
     *
     * @param UserInterface $user
     * @param string $verb
     * @param string $name
     * @param string $template
     * @param string $templatesPlural
     * @return MessageTypesModel
     */
    public static function create(UserInterface $user, string $verb, string $name, string $template = '', string $templatesPlural = ''): MessageTypesModel
    {
        $messageType = new MessageTypesModel();
        $messageType->apps_id = Di::getDefault()->get('app')->getId();
        $messageType->languages_id  = $user->getDefaultCompany()->language;
        $messageType->name = $name;
        $messageType->verb = $verb;
        $messageType->template = $template;
        $messageType->templatesPlural = $templatesPlural;
        $messageType->saveOrFail();

        return $messageType;
    }


    /**
     * Delete an existing message type
     *
     * @param string $uuid
     * @return void
     */
    public static function delete(string $uuid)
    {
        $messageType = MessageTypesModel::findFirstOrFail($uuid);
        return $messageType->deleteOrFail();
    }


    /**
     * Get the message type by its verb
     *
     * @param string $verb
     * @param UserInterface $user
     * @return self
     */
    public static function getTypeByVerb(string $verb): MessageTypesModel
    {
        $messageType = MessageTypesModel::findFirst([
            'conditions' => 'verb = :verb: AND apps_id :currentAppId: AND is_deleted = 0',
            'bind' => [
                'verb' => $verb,
                'currentAppId' => Di::getDefault()->get('app')->getId()
            ]
        ]);

        return $messageType;
    }
}
