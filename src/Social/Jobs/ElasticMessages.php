<?php

namespace Kanvas\Packages\Social\Jobs;

use Baka\Contracts\Queue\QueueableJobInterface;
use Baka\Elasticsearch\Objects\Indices;
use Baka\Jobs\Job;
use Kanvas\Packages\Social\ElasticDocuments\Messages as ElasticDocumentsMessages;
use Kanvas\Packages\Social\Models\Messages;
use Phalcon\Di;

class ElasticMessages extends Job implements QueueableJobInterface
{
    protected ElasticDocumentsMessages $elasticMessage;
    protected Messages $message;

    /**
     * Construct.
     *
     * @param Messages $reaction
     */
    public function __construct(Messages $message)
    {
        $elasticMessage = new ElasticDocumentsMessages();
        $elasticMessage->setData($message->getId(), [$message]);

        $this->elasticMessage = $elasticMessage;
        $this->message = $message;
    }

    /**
     * Handle that delete the message contains in user Message.
     *
     * @return bool
     */
    public function handle() : bool
    {
        Indices::createIfNotExist($this->elasticMessage);
        $log = null;
        if (!$this->message->isIndexable()) {
            $this->elasticMessage->add();
            $log = 'Add';
        } else {
            $this->elasticMessage->delete();
            $log = 'Remove';
        }

        Di::getDefault()->get('log')->info($log . ' message to elastic ' . $this->elasticMessage->getId());

        return true;
    }
}
