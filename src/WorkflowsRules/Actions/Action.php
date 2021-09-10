<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Canvas\Models\SystemModules;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\ActionInterfaces;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Kanvas\Packages\WorkflowsRules\Models\Rules;
use Kanvas\Packages\WorkflowsRules\Models\WorkflowsLogsActions;
use Kanvas\Packages\WorkflowsRules\Thread;

class Action implements ActionInterfaces
{
    protected ?array $results = null;
    protected ?string $error = null;
    protected int $status;
    protected Rules $rules;
    protected Thread $logs;
    protected array $params;

    public const SUCCESSFUL = 1;
    public const FAIL = 0;

    /**
     * __construct.
     *
     * @param  Rules $rules
     * @param  WorkflowsLogsActions $logs
     *
     * @return void
     */
    public function __construct(Rules $rules, Thread $logs)
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


    /**
     * formatArgs.
     *
     * @param  mixed $args
     *
     * @return array
     */
    public function formatArgs(...$args) : array
    {
        $data = [];
        foreach ($args as $arg) {
            $systemModules = SystemModules::getByModelName(get_class($arg));
            $data[$systemModule->slug] = $arg;
        }
        return $data;
    }
}
