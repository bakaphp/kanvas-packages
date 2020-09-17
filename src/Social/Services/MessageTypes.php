<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\MessageTypes as MessageTypesModel;
use Phalcon\Di;

class MessageTypes
{

    /**
     * Return a Message object by its id
     *
     * @param string $uuid
     * @return MessageTypesModel
     */
    public static function get(string $uuid): MessageTypesModel
    {
        $messageType = MessageTypesModel::findFirstOrFail($uuid);
        
        return $messageType;
    }

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
     * @param MessageTypesModel $messageType
     * @return bool
     */
    public static function delete(MessageTypesModel $messageType): bool
    {
        return (bool) $messageType->softDelete();
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
        return MessageTypesModel::findFirst([
            'conditions' => 'verb = :verb: AND apps_id = :currentAppId: AND is_deleted = 0',
            'bind' => [
                'verb' => $verb,
                'currentAppId' => Di::getDefault()->get('app')->getId()
            ]
        ]);
    }
}
