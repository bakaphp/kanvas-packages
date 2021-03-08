<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Models\Interactions;
use Kanvas\Packages\Social\Models\MessageComments;
use Kanvas\Packages\Social\Models\Messages;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Di;

class Comments
{
    /**
     * Get a comment by its ID
     *
     * @param string $uuid
     * @return MessageComments
     */
    public static function getById(string $id): MessageComments
    {
        $comment = MessageComments::getByIdOrFail([
            'conditions' => 'id = :id: and apps_id = :apps_id: and companies_id = :companies_id: and is_deleted = 0',
            'bind' => [
                'id' => $id,
                'apps_id' => Di::getDefault()->get('app')->getId(),
                'companies_id' => Di::getDefault()->get('userData')->getCurrentCompany()->getId()
            ]
        ]);

        return $comment;
    }

    /**
     * Create a comment for a message
     *
     * @param string $messageId
     * @param string $message
     * @return MessageComments
     */
    public static function add(string $messageId, string $message): MessageComments
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
    public static function delete(string $commentId, UserInterface $user): bool
    {
        $comment = MessageComments::getByIdOrFail($commentId);
        $comment->deleteInteraction(Interactions::COMMENT, $user);
        return (bool) $comment->softDelete();
    }

    /**
     * Reply a comment by its Id
     *
     * @param string $commentId
     * @param string $message
     * @return MessageComments
     */
    public static function reply(string $commentId, string $message): MessageComments
    {
        $comment = MessageComments::getByIdOrFail($commentId);
        
        return $comment->reply($message);
    }

    /**
     * Get comments from a message
     *
     * @param Messages $message
     * @return Simple
     */
    public static function getCommentsFromMessage(Messages $message): Simple
    {
        $comments = $message->getMessageComments();
        
        return $comments;
    }
}
