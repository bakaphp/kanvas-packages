<?php

namespace Kanvas\Packages\Tests\Integration\Social\Service;

use IntegrationTester;
use Kanvas\Packages\Social\Models\Interactions as ModelsInteractions;
use Kanvas\Packages\Social\Models\MessageComments;
use Kanvas\Packages\Social\Models\Messages;
use Kanvas\Packages\Social\Services\Comments;
use Kanvas\Packages\Social\Services\Interactions;
use Kanvas\Packages\Social\Services\Reactions;
use Kanvas\Packages\Test\Support\Models\Users;
use Kanvas\Packages\Social\Services\Messages as MessagesService;
use Kanvas\Packages\Social\Services\MessageTypes;

class CommentsCest
{
    public MessageComments $comment;

    /**
     * Get the first comment
     *
     * @return void
     */
    protected function getCommentData(): void
    {
        $this->comment = MessageComments::findFirst();
    }
    
    /**
     * Test add comment
     *
     * @param UnitTester $I
     * @return void
     */
    public function addComment(IntegrationTester $I): void
    {

        //Create a new message type
        MessageTypes::create(new Users(), 'comments', 'Test Type');

        $text = [
            'text' => 'Test some messages'
        ];
        
        //Create a new Message for the comment
        $feed = MessagesService::create(new Users(), 'comments', $text);

        // $feed = Messages::findFirst();
        $comment = Comments::add($feed->getId(), 'test-text');

        $I->assertEquals('test-text', $comment->message);
    }

    /**
     * Test comment edit
     *
     * @param IntegrationTester $I
     * @before getCommentData
     * @return void
     */
    public function editComment(IntegrationTester $I): void
    {
        $comment = Comments::edit((string) $this->comment->getId(), 'edited-test-text');

        $I->assertEquals('edited-test-text', $comment->message);
    }

    /**
     * Test get Comment
     *
     * @param IntegrationTester $I
     * @before getCommentData
     * @return void
     */
    public function getComment(IntegrationTester $I): void
    {
        $comment = Comments::getById($this->comment->getId());

        $I->assertNotNull($comment->getId());
    }

    /**
     * Test reply comment
     *
     * @param IntegrationTester $I
     * @before getCommentData
     * @return void
     */
    public function replyComment(IntegrationTester $I): void
    {
        $reply = Comments::reply($this->comment->getId(), 'reply-test');

        $I->assertEquals($reply->message, 'reply-test');
        $I->assertEquals($reply->parent_id, $this->comment->getId());
    }

    /**
     * Test edit comment
     *
     * @param IntegrationTester $I
     * @before getCommentData
     * @return void
     */
    public function deleteComment(IntegrationTester $I): void
    {
        $I->assertTrue(
            Comments::delete(
                (string) $this->comment->getId(),
                new Users()
            )
        );
    }

    /**
     * Test comments Reactions
     *
     * @param IntegrationTester $I
     * @before getCommentData
     * @return void
     */
    public function commentReaction(IntegrationTester $I): void
    {
        $I->assertFalse(Reactions::addMessageReaction('confuse', new Users(), $this->comment));
        $I->assertFalse(Reactions::addMessageReaction('☺', new Users(), $this->comment));

        $I->assertTrue(Reactions::addMessageReaction('confuse', new Users(), $this->comment));
        $I->assertTrue(Reactions::addMessageReaction('☺', new Users(), $this->comment));

    }

    /**
     * Test Users Comments Interaction
     *
     * @param IntegrationTester $I
     * @before getCommentData
     * @return void
     */
    public function messageInteraction(IntegrationTester $I): void
    {
        $I->assertFalse(
            Interactions::add(new Users(), $this->comment, ModelsInteractions::REACT)
        );
    }

    /**
     * Test method to get comments from a message
     *
     * @param IntegrationTester $I
     * @before getCommentData
     * @return void
     */
    public function getCommentsFromMessage(IntegrationTester $I): void
    {
        $message = $this->comment->getMessage();
        $comments = Comments::getCommentsFromMessage($message);

        $I->assertNotEmpty($comments->toArray());
    }
}
