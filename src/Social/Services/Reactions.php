<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Services;

use Exception;
use Kanvas\Packages\Social\Contract\Users\UserInterface;
use Kanvas\Packages\Social\Jobs\RemoveMessagesReactions;
use Kanvas\Packages\Social\Models\Reactions as ReactionsModel;
use Kanvas\Packages\Social\Models\UsersReactions;
use Kanvas\Packages\Social\Utils\StringFormatter;
use Phalcon\Di;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Mvc\ModelInterface;

class Reactions
{
    /**
     * Add a new reaction to the current entity
     *
     * @param string $reaction
     * @param UserInterface $user
     * @param ModelInterface $entity
     * @return UsersReactions
     */
    public static function addMessageReaction(string $reaction, UserInterface $user, ModelInterface $entity): UsersReactions
    {
        if (StringFormatter::isStringEmoji($reaction)) {
            $reactionData = self::getReactionByEmoji($reaction, $user);
        } else {
            $reactionData = self::getReactionByName($reaction, $user);
        }

        $userReaction = $entity->getReaction(
            [
                'conditions' => 'users_id = :user_id: AND reactions_id = :reaction_id: AND is_deleted = 0',
                'bind' => [
                    'user_id' => $user->getId(),
                    'reaction_id' => $reactionData->getId()
                ]
            ]
        );

        if (!$userReaction) {
            $userReaction = new UsersReactions();
            $userReaction->users_id = $user->getId();
            $userReaction->reactions_id = $reactionData->getId();
            $userReaction->entity_id = $entity->getId();
            $userReaction->entity_namespace = get_class($entity);
            $userReaction->save();
        }

        return $userReaction;
    }

    /**
     * Return an reaction object by its name
     *
     * @param string $reactionName
     * @param UserInterface $user
     * @return ReactionsModel
     */
    public static function getReactionByName(string $reactionName, UserInterface $user): ReactionsModel
    {
        return ReactionsModel::findFirstOrFail([
            'conditions' => 'name = :reaction: AND apps_id = :appId: AND companies_id = :companyId: and is_deleted = 0',
            'bind' => [
                'reaction' => $reactionName,
                'companyId' => $user->getDefaultCompany()->getId(),
                'appId' => Di::getDefault()->get('app')->getId()
            ]
        ]);
    }

    /**
     * Return an reaction object by its icon
     *
     * @param string $reactionEmoji
     * @param UserInterface $user
     * @return ReactionsModel
     */
    public static function getReactionByEmoji(string $reactionEmoji, UserInterface $user): ReactionsModel
    {
        return ReactionsModel::findFirstOrFail([
            'conditions' => "icon = :emoji: AND apps_id = :appId: AND companies_id = :companyId: AND is_deleted = 0",
            'bind' => [
                'emoji' => $reactionEmoji,
                'companyId' => $user->getDefaultCompany()->getId(),
                'appId' => Di::getDefault()->get('app')->getId()
            ]
        ]);
    }

    /**
     * Create a new reaction with or without emoji, $reactionEmoji must be an unicode valid emoji
     *
     * @param string $reactionName
     * @param UserInterface $user
     * @param string $reactionEmoji
     * @return Reactions
     */
    public static function createReaction(string $reactionName, UserInterface $user, string $reactionEmoji = null): ReactionsModel
    {
        if ($reactionEmoji && !StringFormatter::isStringEmoji($reactionEmoji)) {
            throw new Exception('Emoji must have a valid unicode format');
        }

        $reaction = new ReactionsModel();
        $reaction->name = $reactionName;
        $reaction->apps_id = Di::getDefault()->get('app')->getId();
        $reaction->companies_id = $user->getDefaultCompany()->getId();
        $reaction->icon = $reactionEmoji;
        $reaction->saveOrFail();

        return $reaction;
    }

    /**
     * Return the group of reactions that have emojis and bellow to the current app
     *
     * @return Simple
     */
    public static function getReactionsEmojis(): Simple
    {
        return ReactionsModel::find([
            'conditions' => "icon IS NOT NULL AND apps_id = :appId: AND is_deleted = 0",
            'bind' => [
                'appId' => Di::getDefault()->get('app')->getId()
            ]
        ]);
    }

    /**
     * Delete a Reaction by its id.
     *
     * @param integer $reactionId
     * @return void
     */
    public static function deleteReaction(int $reactionId): void
    {
        $reaction = ReactionsModel::getByIdOrFail($reactionId);
        $reaction->deleteOrFail();
        RemoveMessagesReactions::dispatch($reaction);
    }

    /**
     * Edit a reaction
     *
     * @param string $reactionId
     * @param string $name
     * @return ReactionsModel
     */
    public static function editReaction(string $reactionId, string $name): ReactionsModel
    {
        $reaction = ReactionsModel::getByIdOrFail($reactionId);
        $reaction->name = $name;
        $reaction->save();

        return $reaction;
    }
}
