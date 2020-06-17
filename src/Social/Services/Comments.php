<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Exception;
use Kanvas\Packages\Social\Models\MessageComments;
use Kanvas\Packages\Social\Models\Messages;
use Phalcon\Di;

class Comments
{
    /**
     * Create a comment for a message
     *
     * @param string $messageId
     * @param string $message
     * @return MessageComments
     */
    public static function add(int $messageId, string $message): MessageComments
    {
        $messageData = Messages::getByIdOrFail($messageId);

        return $messageData->comment($message);
    }

    /**
     * Update a comment by its id
     *
     * @param string $commentId
     * @param string $message
     * @return MessageComments
     */
    public static function edit(string $commentId, string $message): MessageComments
    {
        $comment = MessageComments::getByIdOrFail($commentId);
        $comment->message = $message;
        $comment->updateOrFail();

        return $comment;
    }
    
    /**
     * Delete a comment
     *
     * @param string $commentId
     * @return boolean
     */
    public static function delete(string $commentId): bool
    {
        $comment = MessageComments::getByIdOrFail($commentId);
        return (bool) $comment->softDelete();
    }
}
