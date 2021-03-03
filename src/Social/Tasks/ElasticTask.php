<?php

namespace Kanvas\Packages\Social\Tasks;

use Baka\Contracts\Elasticsearch\IndexBuilderTaskTrait;
use Canvas\Cli\Tasks\ElasticTask as KanvasElasticTask;
use Kanvas\Packages\Social\ElasticDocuments\Messages as MessageDocument;
use Kanvas\Packages\Social\Models\Messages as MessagesModel;
use Kanvas\Packages\Social\Models\Channels as ChannelsModel;
use Baka\Elasticsearch\Objects\Indices;
use Phalcon\Di;

class ElasticTask extends KanvasElasticTask
{
    /**
     * Handle the index of the document.
     *
     * @return void
     */
    public function indexDocumentAction()
    {
        //if the index doesn't exist create it
        $messages = new MessageDocument();
        $messagesRecords = MessagesModel::findOrFail();

        foreach ($messagesRecords as $message) {
            $messages->setData($message->id, []);

            if (!Indices::exist($messages->getIndices())) {
                Indices::create($messages, 3, 1000);
            }
            $result = $messages->add();
            Di::getDefault()->get('log')->info('Vehicle Add to Index BrowseVehicle', [$result]);
        }
        
        return true;
    }
}