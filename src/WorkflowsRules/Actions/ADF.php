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
     * @param  WorkflowsEntityInterfaces $entity
     * @param  array $params
     * @param mixed ...$args
     *
     * @return array
     */
    public function handle(WorkflowsEntityInterfaces $entity, array $params = [], ...$args) : array
    {
        $response = null;
        $di = Di::getDefault();
        try {
            $transformer = Hengen::getTransformer('ADF', $entity, $params, ...$args);
            $communicator = Hengen::getCommunication($transformer, $entity->companies);
            $this->data = $transformer->getData();
            $this->status = 1;
            $this->message = $transformer->toFormat();
            $communicator->send();
        } catch (Throwable $e) {
            $this->message = 'Error processing lead - ' . $e->getMessage();
            $di->get('log')->error('Error processing lead - ' . $e->getMessage(), [$e->getTraceAsString()]);
            $this->status = 0;
            $response = $e->getTraceAsString();
        }

        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
            'body' => $response
        ];
    }
}
