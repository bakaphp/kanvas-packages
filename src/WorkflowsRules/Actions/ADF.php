<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Kanvas\Hengen\Hengen;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Phalcon\Di;
use Throwable;

class ADF extends Action
{
    /**
     * handle.
     *
     * @param WorkflowsEntityInterfaces $entity
     * @param array $params
     * @param mixed ...$args
     *
     * @return void
     */
    public function handle(WorkflowsEntityInterfaces $entity, ...$args) : void
    {
        $response = null;
        $di = Di::getDefault();

        try {
            $transformer = Hengen::getTransformer(
                'ADF',
                $entity,
                $this->params,
                ...$args
            );

            $communicator = Hengen::getCommunication(
                $transformer,
                $entity->companies
            );

            $this->data = $transformer->getData();
            $this->status = 1;
            $this->message = $transformer->toFormat();
            $communicator->send();

            $this->setResults([
                'html' => $transformer->toFormat(),
                'data' => $transformer->getData()
            ]);

            $this->setStatus(Action::SUCCESSFUL);
        } catch (Throwable $e) {
            $this->setError($e->getMessage());
            $this->setStatus(Action::FAIL);
        }
    }
}
