<?php
declare(strict_types=1);

namespace Kanvas\Packages\Social\Models;

class Channels extends BaseModel
{
    public $id;
    public string $name;
    public string $slug;
    public string $description;
    public string $entity_namespace;
    public string $entity_id;
    public ?int $last_message_id = null;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();

        $this->setSource('channels');

        $this->hasManyToMany(
            'id',
            ChannelMessages::class,
            'channel_id',
            'messages_id',
            Messages::class,
            'id',
            [
                'alias' => 'messages',
                'reusable' => true
            ]
        );
    }

    /**
     * Get Channel by name.
     *
     * @param string $channelName
     *
     * @return self
     */
    public static function getByName(string $channelName) : self
    {
        return self::findFirstOrFail([
            'conditions' => 'name = :channelName: AND is_deleted = 0',
            'bind' => [
                'channelName' => $channelName
            ]
        ]);
    }
}
