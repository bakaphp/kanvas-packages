<?php

namespace Kanvas\Packages\WorkflowsRules\Contracts\Interfaces;

interface ActionInterfaces
{
    public function handle(WorkflowsEntityInterfaces $entity, ...$args) : void;

    public function setStatus(int $status) : void;

    public function getStatus() : int;

    public function setResults(array $result) : void;

    public function getResults() : ?array;

    public function setError(string $error) : void;

    public function getError() : ?string;
}
