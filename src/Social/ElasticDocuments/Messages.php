<?php

namespace Kanvas\Packages\Social\ElasticDocuments;

use Baka\Elasticsearch\Objects\Documents;
use Kanvas\Packages\Social\Models\Messages as MessagesModel;
use Kanvas\Packages\Social\Models\ChannelMessages as ChannelMessagesModel;

class Messages extends Documents
{
    /**
     * initialize.
     *
     * @return void
     */
    public function initialize() : void
    {
        $this->setIndices('messages');
        $this->addRelation('channel_messages', ['alias' => 'channel_messages', 'elasticAlias' => 'chmsgs', 'elasticIndex' => 1]);
        $this->addRelation('companies', ['alias' => 'companies', 'elasticAlias' => 'cmps', 'elasticIndex' => 1]);
        $this->addRelation('apps', ['alias' => 'apps', 'elasticAlias' => 'apps', 'elasticIndex' => 1]);
        $this->addRelation('users', ['alias' => 'users', 'elasticAlias' => 'usrs', 'elasticIndex' => 1]);
    }
    
    /**
     * structure
     *
     * @return array
     */
    public function structure() : array
    {
        return [
            'id' => $this->integer,
            'apps_id' => $this->integer,
            'companies_id' => $this->integer,
            'users_id' => $this->integer,
            'message_types_id' => $this->integer,
            'message' => $this->text,
            'channel_messages' => [
                'channel_id' => $this->integer,
                'message_id' => $this->integer,
                'users_id' => $this->integer,
            ]
        ];
    }

    /**
     * setData.
     *
     * @param  mixed $id
     * @param  array $data
     *
     * @return void
     */
    public function setData($id, array $data) : Documents
    {
        parent::setData($id, $data);
        $message = MessagesModel::findFirstOrFail($id);
        $this->data = [
            'id' => (int)$message->id,
            'apps_id' => $message->apps_id,
            'companies_id' => $message->companies_id,
            'users_id' => $message->users_id,
            'message_types_id' => $message->message_types_id,
            'message' => $message->message,
            'channel_messages' => [
                'channel_id' => $message->channels->id,
                'message_id' => (int)$message->id,
                'users_id' => $message->users_id,
            ]
        ];
        return $this;
    }
}
