<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules;

use Kanvas\Packages\WorkflowsRules\Actions\ADF;
use Kanvas\Packages\WorkflowsRules\Actions\PDF;
use Kanvas\Packages\WorkflowsRules\Actions\SendMail;
use Kanvas\Packages\WorkflowsRules\Actions\SendToZoho;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\ActionInterfaces;
use Kanvas\Packages\WorkflowsRules\Models\Rules;

class Actions
{
    /**
     * getAction.
     *
     * @param  mixed $action
     * @param  mixed $rule
     *
     * @return ActionInterfaces
     */
    public static function getAction(string $actionClass, Rules $rule, Thread $thread) : ActionInterfaces
    {
        $params = json_decode($rule->params, true);
        switch ($actionClass) {
            case PDF::class:
                $action = new PDF($rule, $thread);
                $action->setParams($params);
                return $action;
                break;
            case ADF::class:
                $action = new ADF($rule, $thread);
                $action->setParams($params);
                return $action;
                break;
            case SendMail::class:
                $action = new SendMail($rule, $thread);
                $action->setParams($params);
                return $action;
                break;
            case SendToZoho::class:
                $action = new SendToZoho($rule, $thread);
                $action->setParams($params);
                return $action;
                break;
        }
    }
}
