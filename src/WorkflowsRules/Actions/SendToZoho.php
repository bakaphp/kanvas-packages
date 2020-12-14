<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Phalcon\Di;
use Throwable;
use Zoho\CRM\ZohoClient;

class SendToZoho extends Action
{
    /**
     * handle.
     *
     * @param  WorkflowsEntityInterfaces $entity
     * @param  array $params
     *
     * @return array
     */
    public function handle(WorkflowsEntityInterfaces $entity, array $params = []) : array
    {
        $response = null;
        try {
            $di = Di::getDefault();
            $companyId = $entity->companies_id;
            $di->get('log')->info('Start Process Leads For company ' . $companyId);

            $zohoClient = new ZohoClient();

            ///get from db
            $zohoClient->setAuthRefreshToken($entity->getCompanies()->get('ZOHO_AUTH_REFRESH_TOKEN'));
            $zohoClient->setZohoClientId($entity->getCompanies()->get('ZOHO_CLIENT_ID'));
            $zohoClient->setZohoClientSecret($entity->getCompanies()->get('ZOHO_CLIENT_SECRET'));

            $refresh = $zohoClient->manageAccessTokenRedis($di->get('redis'), 'zoho_client' . $companyId);
            $zohoClient->setModule('Leads');

            $request = [
                'First_Name' => $entity->firstname,
                'Last_Name' => $entity->lastname,
                'Lead_Source' => $entity->leads_receivers ? $entity->leads_receivers->name : '',
                'Phone' => $entity->phone,
                'Email' => $entity->email,
            ];
            $customFields = $entity->getAll();
            $request = array_merge($customFields, $request);
            $di->get('log')->info('Data lead', $request);
            $this->data = $request;

            $response = $zohoClient->insertRecords(
                $request,
                ['wfTrigger' => 'true']
            );
            $response = $response->getResponse();
            if (!empty($response['recordId'])) {
                $entity->saveLinkedSources($response);
            }

            $this->data = $request;
            $this->message = 'Process Leads For company ' . $companyId;
            $this->status = 1;
            $di->get('log')->info('Process Leads For company ' . $companyId, [$response]);
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
