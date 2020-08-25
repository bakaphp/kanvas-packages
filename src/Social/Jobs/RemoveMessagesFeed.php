<?php

namespace Kanvas\Packages\Social\Jobs;

use Baka\Contracts\Queue\QueueableJobInterface;
use Baka\Jobs\Job;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\Social\Models\UserMessages;
use Phalcon\Di;

class RemoveMessagesFeed extends Job implements QueueableJobInterface
{
    protected $message;

    /**
     * Construct
     *
     * @param Messages $reaction
     */
    public function __construct(Messages $message)
    {
        $this->message = $message;
    }

    /**
     * Handle that delete the message contains in user Message.
     * @return bool
     */
    public function handle(): bool
    {
        $userMessages = UserMessages::find([
            'conditions' => 'messages_id = :message_id: AND is_deleted = 0',
            'bind' => [
                'message_id' => $this->message->getId()
            ]
        ]);

        foreach ($userMessages as $userMessage) {
            $userMessage->softDelete();
        }

        Di::getDefault()->get('log')->info('Delete message from users feeds: ' . $this->message->getId());

        return true;
    }
}
