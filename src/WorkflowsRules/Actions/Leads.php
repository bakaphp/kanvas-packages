<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Phalcon\Di;
use Throwable;

class Leads extends Action
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
            $message = $args[0]->messages();
            $leads = [];
            foreach ($message['data']['referrals'] as $referral) {
                $leadClass = get_class($entity);
                $lead = new $leadClass;
                $lead->users_id = $entity->users_id;
                $lead->companies_id = $entity->companies_id;
                $lead->companies_branches_id = $entity->companies_branches_id;
                $lead->leads_status_id = 1;
                $lead->leads_receivers_id = $entity->leads_receivers_id;
                $referral['firstname'] = $referral['name'];
                $lead->saveOrFail($referral);

                // here must be the code for save the people and contact


                $leads[] = $lead->toArray();
            }
            $this->data = [$message['data'], $leads];
            $this->status = Action::SUCCESSFUL;
        } catch (Throwable $e) {
            $this->message = 'Error processing lead - ' . $e->getMessage();
            $di->get('log')->error('Error processing lead - ' . $e->getMessage(), [$e->getTraceAsString()]);
            $this->status = Action::FAIL;
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
