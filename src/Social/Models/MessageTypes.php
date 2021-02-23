<?php

namespace Kanvas\Packages\Social\Models;

class MessageTypes extends BaseModel
{
    public $id;
    public $apps_id;
    public $languages_id;
    public $name;
    public $verb;
    public $template;
    public $templates_plura;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('message_types');

        $this->hasMany(
            'id',
            Messages::class,
            'message_types_id',
            [
                'alias' => 'messages',
                'params' => [
                    'conditions' => 'is_deleted = 0'
                ]
            ]
        );

        $this->hasMany(
            'id',
            AppModuleMessage::class,
            'message_types_id',
            [
                'alias' => 'appModules'
            ]
        );
    }

    /**
     * Return a Message object by its id
     *
     * @param string $uuid
     * @return MessageTypesModel
     */
    public static function getByUuid(string $uuid): MessageTypesModel
    {
        $messageType = MessageTypes::findFirstOrFail([
            'conditions' => 'uuid = :uuid: and is_deleted = 0',
            'bind' => [
                'uuid' => $uuid
            ]
        ]);
        
        return $messageType;
    }

    /**
    * Get the message type by its verb
    *
    * @param string $verb
    * @param UserInterface $user
    * @return MessageTypesModel | null
    */
    public static function getTypeByVerb(string $verb) : ?MessageTypesModel
    {
        return MessageTypes::findFirst([
            'conditions' => 'verb = :verb: AND apps_id = :currentAppId: AND is_deleted = 0',
            'bind' => [
                'verb' => $verb,
                'currentAppId' => Di::getDefault()->get('app')->getId()
            ]
        ]);
    }
}
