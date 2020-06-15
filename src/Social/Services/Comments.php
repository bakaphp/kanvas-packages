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
    public static function add(string $messageId, string $message): MessageComments
    {
        try {
            $message = Messages::getByIdOrFail($messageId);

            $comment = new MessageComments();
            $comment->message_id = $message->getId();
            $comment->apps_id = Di::getDefault()->get('app')->getId();
            $comment->companies_id = Di::getDefault()->get('userData')->getDefaultCompany()->getId();
            $comment->users_id = Di::getDefault()->get('userData')->getId();
            $comment->message = $message;
            $comment->saveOrFail();

            return $comment;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
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
