<?php
declare(strict_types=1);

namespace Kanvas\Packages\Social\Jobs;

use Baka\Contracts\Queue\QueueableJobInterface;
use Baka\Jobs\Job;
use Kanvas\Packages\Social\Models\Reactions as ReactionsModels;
use Kanvas\Packages\Social\Models\UsersReactions;
use Phalcon\Di;

class RemoveMessagesReactions extends Job implements QueueableJobInterface
{
    protected $reaction;

    /**
     * Construct.
     *
     * @param ReactionsModels $reaction
     */
    public function __construct(ReactionsModels $reaction)
    {
        $this->reaction = $reaction;
    }

    /**
     * Handle that delete the reactions contains in any entity.
     * Todo: Remove reaction count for the entity.
     *
     * @return bool
     */
    public function handle() : bool
    {
        $messagesReaction = UsersReactions::find([
            'conditions' => 'reactions_id = :reaction_id: AND is_deleted = 0',
            'bind' => [
                'reaction_id' => $this->reaction->getId()
            ]
        ]);

        foreach ($messagesReaction as $messageReaction) {
            $messageReaction->delete();
        }

        Di::getDefault()->get('log')->info('Delete message reactions from reaction id: ' . $this->reaction->getId());

        return true;
    }
}
