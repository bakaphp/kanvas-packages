<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\IAction;
use Phalcon\Di;

class SendMail implements IAction
{
    public function handle(object $entity, array $params = [])
    {
        $mailManager = Di::getDefault()->get('mail');
        foreach ($params as $mail) {
            $mailManager->to($mail)
            ->subject('Test Normal Email now')
            ->content('send normal email now')
            ->sendNow();
        }
    }
}
