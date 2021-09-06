<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\ActionInterfaces;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;

use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\WorkflowsLogsActions;

class Action implements ActionInterfaces
{
    protected ?array $results = null;
    protected ?string $error = null;
    protected int $status;

    protected Rules $rules;
    protected $logs;

    protected array $params;

    const SUCCESSFUL = 1;

    const FAIL = 0;

    /**
     * __construct.
     *
     * @param  Rules $rules
     * @param  WorkflowsLogsActions $logs
     *
     * @return void
     */
    public function __construct(Rules $rules, $logs)
    {
        $this->rules = $rules;
        $this->logs = $logs;
    }

    /**
     * handle.
     *
     * @param  WorkflowsEntityInterfaces $entity
     * @param  array $params
     *
     * @return array
     */
    public function handle(WorkflowsEntityInterfaces $entity, ...$args) : void
    {
    }

    /**
     * getMessage.
     *
     * @return string
     */
    public function getMessage() : ?string
    {
        return $this->message;
    }

    /**
     * setStatus.
     *
     * @param  int $status
     *
     * @return void
     */
    public function setStatus(int $status) : void
    {
        $this->status = $status;
    }

    /**
     * getStatus.
     *
     * @return bool
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * setParams.
     *
     * @param  array $params
     *
     * @return self
     */
    public function setParams(array $params) : self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * setResults.
     *
     * @param  mixed $result
     *
     * @return void
     */
    public function setResults(array $result) : void
    {
        $this->results = $result;
    }

    /**
     * getResults.
     *
     * @return array
     */
    public function getResults() : ?array
    {
        return $this->results;
    }


    /**
     * setError.
     *
     * @param  string $error
     *
     * @return void
     */
    public function setError(string $error) : void
    {
        $this->error = $error;
    }

    /**
     * getError.
     *
     * @return string
     */
    public function getError() : ?string
    {
        return $this->error;
    }
}
