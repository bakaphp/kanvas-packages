<?php

namespace Kanvas\Packages\Social\Contract\Comments;

use Phalcon\Http\Response;
use Kanvas\Packages\Social\Services\Comments;
use Gewaer\Models\MessageComments;
use Baka\Validation as CanvasValidation;
use Phalcon\Validation\Validator\PresenceOf;

/**
 * Channels Trait
 */
trait CommentsTrait
{
    /**
     * Get all the comments
     *
     * @param int $messageId
     * @throws Exception
     *
     * @return Response
     */
    public function getAllComments(int $messageId) : Response
    {
        return $this->response($this->processOutput(MessageComments::findOrFail([
            "conditions" => "message_id = :message_id: and apps_id = :apps_id: and companies_id = :companies_id: and is_deleted = 0",
            "bind" => [
                "message_id" => $messageId,
                "apps_id" => $this->app->getId(),
                "companies_id" =>$this->userData->getCurrentCompany()->getId(),
            ]
        ])));
    }

    /**
     * Get comment by its id
     *
     * @param int $commentId
     * @return Response
     */
    public function getComment(int $commentId): Response
    {
        return $this->response(Comments::getById((string)$commentId));
    }

    /**
     * processInput function.
     *
     * @param array $request
     * @return array
     */
    protected function processInput(array $request): array
    {
        $validation = new CanvasValidation();
        $validation->add('message', new PresenceOf(['message' => _('message is required.')]));
        $validation->validate($request);

        return $request;
    }

    /**
     * Add a new comment to a message
     *
     * @param int $messageId
     * @return Response
     */
    public function addComment(int $messageId): Response
    {
        $request = $this->processInput($this->request->getPostData());
        $newComment = Comments::add($messageId, $request['message']);

        return $this->response($newComment);
    }

    /**
     * Add a new comment to a message
     *
     * @param int $messageId
     * @return Response
     */
    public function editComment(int $commentId): Response
    {
        $request = $this->request->getPutData();

        $validation = new CanvasValidation();
        $validation->add('message', new PresenceOf(['message' => _('message is required.')]));
        $validation->validate($request);

        $newComment = Comments::edit($commentId, $request['message']);

        return $this->response($newComment);
    }

    /**
     * Add a new comment to a message
     *
     * @param int $commentId
     * @return Response
     */
    public function deleteComment(int $commentId): Response
    {
        return $this->response(Comments::delete($commentId, $this->userData));
    }
}
