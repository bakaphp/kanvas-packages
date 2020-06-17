<?php
declare(strict_types=1);
namespace Kanvas\Packages\Social\Listener;

use Kanvas\Packages\Social\Services\Interactions;
use Kanvas\Packages\Social\Models\Interactions as InteractionsModel;
use Phalcon\Di;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Events\Event;

class Users
{
    public function save(Event $event, ModelInterface $entity): void
    {
        Interactions::add(Di::getDefault()->get('userData'), $entity, InteractionsModel::SAVE);
    }
    public function liked(Event $event, ModelInterface $entity): void
    {
        Interactions::add(Di::getDefault()->get('userData'), $entity, InteractionsModel::LIKE);
    }
    public function comment(Event $event, ModelInterface $entity): void
    {
        Interactions::add(Di::getDefault()->get('userData'), $entity, InteractionsModel::COMMENT);
    }
    public function reply(Event $event, ModelInterface $entity): void
    {
        Interactions::add(Di::getDefault()->get('userData'), $entity, InteractionsModel::REPLIED);
    }
    public function follow(Event $event, ModelInterface $entity): void
    {
        Interactions::add(Di::getDefault()->get('userData'), $entity, InteractionsModel::FOLLOWING);
    }
}
