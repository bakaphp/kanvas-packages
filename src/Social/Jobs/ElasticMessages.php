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
    protected ElasticDocumentsMessages $message;

    /**
     * Construct.
     *
     * @param Messages $reaction
     */
    public function __construct(Messages $message)
    {
        $elasticMessage = new ElasticDocumentsMessages();
        $elasticMessage->setData($message->getId(), [$message]);

        $this->message = $elasticMessage;
    }

    /**
     * Handle that delete the message contains in user Message.
     *
     * @return bool
     */
    public function handle() : bool
    {
        Indices::createIfNotExist($this->message);
        $this->message->add();

        Di::getDefault()->get('log')->info('Delete message from users feeds: ' . $this->message->getId());

        return true;
    }
}
