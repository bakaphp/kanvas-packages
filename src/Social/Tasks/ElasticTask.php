<?php

namespace Kanvas\Packages\Social\Tasks;

use Baka\Elasticsearch\Objects\Indices;
use Canvas\Cli\Tasks\ElasticTask as KanvasElasticTask;
use Kanvas\Packages\Social\ElasticDocuments\Messages as MessageDocument;
use Kanvas\Packages\Social\Models\Messages as MessagesModel;
use Phalcon\Di;

class ElasticTask extends KanvasElasticTask
{
    /**
     * Handle the index of the document.
     *
     * @return void
     */
    public function indexMessages()
    {
        //if the index doesn't exist create it
        $messages = new MessageDocument();
        $messagesRecords = MessagesModel::findOrFail([
            'conditions' => 'apps_id = :apps_id: and is_deleted = 0',
            'bind' => [
                'apps_id' => Di::getDefault()->get('app')->getId(),
            ]
        ]);
        // $messagesRecords = MessagesModel::findOrFail(['limit' => 1]);

        foreach ($messagesRecords as $message) {
            $messages->setData($message->id, []);

            if (!Indices::exist($messages->getIndices())) {
                Indices::create($messages, 3, 1000);
            }
            $result = $messages->add();
            Di::getDefault()->get('log')->info('Messages added to Messages Index', [$result]);
        }

        return true;
    }
}
