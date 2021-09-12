<?php

namespace Kanvas\Packages\WorkflowsRules;

use Baka\Contracts\Database\ModelInterface;
use Canvas\Models\SystemModules;
use Kanvas\Packages\WorkflowsRules\Contracts\ActionInterfaces;
use Kanvas\Packages\WorkflowsRules\Contracts\WorkflowsEntityInterfaces;
use Kanvas\Packages\WorkflowsRules\Models\Rules;

abstract class Actions implements ActionInterfaces
{
    protected ?array $results = null;
    protected ?string $error = null;
    protected ?String $message = null;
    protected int $status = 0;
    protected Rules $rules;
    protected Thread $logs;
    protected array $params;
    public const SUCCESSFUL = 1;
    public const FAIL = 0;

    /**
     * __construct.
     *
     * @param Rules $rules
     * @param Thread $logs
     *
     * @return void
     */
    public function __construct(Rules $rules, Thread $logs)
    {
        $this->rules = $rules;
        $this->logs = $logs;
        $this->setParams($this->rules->getParams());
    }

    /**
     * handle.
     *
     * @param WorkflowsEntityInterfaces $entity
     *
     * @return void
     */
    abstract public function handle(WorkflowsEntityInterfaces $entity) : void;

    /**
     * getMessage.
     *
     * @return null|string
     */
    public function getMessage() : ?string
    {
        return $this->message;
    }

    /**
     * setStatus.
     *
     * @param int $status
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
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }

    /**
     * setParams.
     *
     * @param array $params
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
     * @param mixed $result
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
     * @param string $error
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
     * @param mixed $args
     *
     * @return array
     */
    public function getModels(...$args) : array
    {
        $data = [];
        foreach ($args as $arg) {
            if (is_array($arg) && $arg instanceof ModelInterface) {
                $systemModules = SystemModules::getByModelName(get_class($arg));
                $data[$systemModules->slug] = $arg;
            }
        }
        return $data;
    }
}
