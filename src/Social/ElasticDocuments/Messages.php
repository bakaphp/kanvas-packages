<?php

namespace Kanvas\Packages\Social\ElasticDocuments;

use Baka\Elasticsearch\Objects\Documents;
use Kanvas\Packages\Social\Models\Messages as MessagesModel;
use Phalcon\Mvc\Model\Resultset\Simple;
use RuntimeException;

class Messages extends Documents
{
    protected int $commentsLimit = 3;

    /**
     * initialize.
     *
     * @return void
     */
    public function initialize() : void
    {
        $this->setIndices('messages');
        $this->addRelation('channel_messages', ['alias' => 'channel_messages', 'elasticAlias' => 'chmsgs', 'elasticIndex' => 1]);
        $this->addRelation('channels', ['alias' => 'channels', 'elasticAlias' => 'chs', 'elasticIndex' => 1]);
        $this->addRelation('companies', ['alias' => 'companies', 'elasticAlias' => 'cmps', 'elasticIndex' => 1]);
        $this->addRelation('apps', ['alias' => 'apps', 'elasticAlias' => 'apps', 'elasticIndex' => 1]);
        $this->addRelation('users', ['alias' => 'users', 'elasticAlias' => 'usrs', 'elasticIndex' => 1]);
        $this->addRelation('comments', ['alias' => 'comments', 'elasticAlias' => 'msgcm', 'elasticIndex' => 1]);
        $this->addRelation('message_type', ['alias' => 'message_type', 'elasticAlias' => 'msgty', 'elasticIndex' => 1]);
    }

    /**
     * structure.
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
            'users' => [
                'id' => $this->integer,
                'firstname' => $this->text,
                'lastname' => $this->text,
                'photo' => $this->text
            ],
            'message_types_id' => $this->integer,
            'message_types' => [
                'id' => $this->integer,
                'apps_id' => $this->integer,
                'languages_id' => $this->text,
                'name' => $this->text,
                'verb' => $this->text,
            ],
            'message' => $this->text,
            'reactions_count' => $this->integer,
            'comments_count' => $this->integer,
            'files' => [],
            'channels' => [
                'id' => $this->integer,
                'name' => $this->text,
                'description' => $this->text,
                'last_message_id' => $this->integer,
                'slug' => $this->text,
                'created_at' => $this->text,
                'is_deleted' => $this->integer,
            ],
            'comments' => [
                'id' => $this->integer,
                'message_id' => $this->integer,
                'apps_id' => $this->integer,
                'companies_id' => $this->integer,
                'users_id' => $this->integer,
                'users' => [
                    'id' => $this->integer,
                    'firstname' => $this->text,
                    'lastname' => $this->text,
                    'photo' => $this->text
                ],
                'message' => $this->text,
            ],
            'created_at' => $this->text,
            'updated_at' => $this->text,
            'is_deleted' => $this->integer
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
        if (!$data[0] instanceof MessagesModel) {
            throw new RuntimeException('Params 0 of data should be the message');
        }

        parent::setData($id, $data);
        $message = $data[0]; //MessagesModel::findFirstOrFail($id);

        $this->data = [
            'id' => (int)$message->id,
            'apps_id' => $message->apps_id,
            'companies_id' => $message->companies_id,
            'users_id' => $message->users_id,
            'users' => [
                'id' => $message->users->id,
                'firstname' => $message->users->firstname,
                'lastname' => $message->users->lastname,
                'photo' => !defined('API_TESTS') ? $message->users->getPhoto()->url : null,
            ],
            'message_types_id' => $message->message_types_id,
            'message_types' => [
                'id' => $message->message_type->id,
                'apps_id' => $message->message_type->apps_id,
                'languages_id' => $message->message_type->languages_id,
                'name' => $message->message_type->name,
                'verb' => $message->message_type->verb,
            ],
            'message' => $message->message,
            'reactions_count' => $message->reactions_count,
            'comments_count' => $message->comments_count,
            'files' => $message->files->toArray(),
            'channels' => $message->channels->getFirst() ? [
                'id' => $message->channels->getFirst()->id,
                'name' => $message->channels->getFirst()->name,
                'description' => $message->channels->getFirst()->description,
                'last_message_id' => $message->channels->getFirst()->last_message_id,
                'slug' => $message->channels->getFirst()->slug,
                'created_at' => $message->channels->getFirst()->created_at,
                'is_deleted' => $message->channels->getFirst()->is_deleted,
            ] : null,
            'comments' => $this->formatComments(
                $message->getComments([
                    'limit' => $this->commentsLimit,
                    'order' => 'id DESC'
                ])
            ),
            'created_at' => $message->created_at,
            'updated_at' => $message->updated_at,
            'is_deleted' => $message->is_deleted
        ];
        return $this;
    }

    /**
     * Format message comments data.
     *
     * @param Simple $comments
     *
     * @return array
     */
    private function formatComments(Simple $comments) : array
    {
        $element = [];
        $data = [];
        foreach ($comments as $comment) {
            $element['id'] = (int)$comment->id;
            $element['message_id'] = $comment->message_id;
            $element['apps_id'] = $comment->apps_id;
            $element['companies_id'] = $comment->companies_id;
            $element['users_id'] = $comment->users_id;
            $element['users']['id'] = (int)$comment->users->id;
            $element['users']['firstname'] = $comment->users->firstname;
            $element['users']['lastname'] = $comment->users->lastname;
            $element['users']['photo'] = $comment->users->getPhoto()->url;
            $element['message'] = $comment->message;
            $data[] = $element;
        }

        return $data;
    }
}
