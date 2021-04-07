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
    public function indexMessagesAction() : void
    {
        //if the index doesn't exist create it
        $messages = new MessageDocument();
        $messagesRecords = MessagesModel::findOrFail([
            'conditions' => 'apps_id = :apps_id: and is_deleted = 0',
            'bind' => [
                'apps_id' => Di::getDefault()->get('app')->getId(),
            ]
        ]);

        foreach ($messagesRecords as $message) {
            if (!is_object($message->users)) {
                continue;
            }
               
            $this->di->set('userData', $message->users);
            $messages->setData($message->id, [$message]);

            if (!Indices::exist($messages->getIndices())) {
                Indices::create($messages, 3, 1000);
            }
            $result = $messages->add();
            Di::getDefault()->get('log')->info('Messages added to Messages Index', [$result]);
        }
    }

    /**
     * Erase messages index
     *
     * @return void
     */
    public function eraseMessagesAction() : void
    {
        echo("Deleting Messages Index...\n");

        $host = getenv('ELASTIC_HOST');
        $output = [];
        system("curl -XDELETE {$host}/messages", $output);
    }
}
