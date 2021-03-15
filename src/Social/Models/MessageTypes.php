<?php

namespace Kanvas\Packages\Social\Models;

use Canvas\Models\Behaviors\Uuid;
use Phalcon\Di;

class MessageTypes extends BaseModel
{
    public $id;
    public string $uuid;
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

        $this->addBehavior(
            new Uuid()
        );

        $this->hasMany(
            'id',
            Messages::class,
            'message_types_id',
            [
                'reusable' => true,
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
                'reusable' => true,
                'alias' => 'appModules'
            ]
        );
    }

    /**
     * Return a Message object by its id.
     *
     * @param string $uuid
     *
     * @return self
     */
    public static function getByUuid(string $uuid) : self
    {
        return MessageTypes::findFirstOrFail([
            'conditions' => 'uuid = :uuid: and is_deleted = 0',
            'bind' => [
                'uuid' => $uuid
            ]
        ]);
    }

    /**
     * Get the message type by its verb.
     *
     * @param string $verb
     * @param UserInterface $user
     *
     * @return self | null
     */
    public static function getTypeByVerb(string $verb) : ?self
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
