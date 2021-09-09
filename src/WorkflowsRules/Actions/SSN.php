<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Kanvas\Packages\Social\Contracts\Messages\MessagesInterface;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Phalcon\Di;
use  Throwable;

class SSN extends Action
{
    const NAME = 'SSN';
    /**
     * handle.
     *
     * @param  WorkflowsEntityInterfaces $entity
     * @param  array $params
     * @param mixed ...$args
     *
     * @return array
     */
    public function handle(WorkflowsEntityInterfaces $entity, ...$args) : void
    {
        $response = null;
        $di = Di::getDefault();

        try {
            foreach ($args as $feed) {
                if (is_subclass_of($feed, MessagesInterface::class)) {
                    $message = json_decode($feed->message, true);
                    unset($message['data']['form']['ssn']);
                    $feed->message = json_encode($message);
                    $feed->saveOrFail();
                }
            }
            $this->setResults($message);
            $this->setStatus(Action::SUCCESSFUL);
        } catch (Throwable $e) {
            $this->setError($e->getMessage());
            $this->setStatus(Action::FAIL);
            $this->setError($e->getMessage());
            $this->setStatus(Action::FAIL);
        }
    }
}
