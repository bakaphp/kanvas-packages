<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\ActionInterfaces;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\ModelInterfaces;
use Phalcon\Di;
use Throwable;
use Zoho\CRM\ZohoClient;

class SendToZoho implements ActionInterfaces
{
    public function handle(ModelInterfaces $entity, array $params = [])
    {
        try {
            $di = Di::getDefault();
            $companyId = $entity->companies_id;
            $di->get('log')->info('Start Process Leads For company ' . $companyId);

            $zohoClient = new ZohoClient();

            ///get from db
            $zohoClient->setAuthRefreshToken($entity->companies->get('ZOHO_AUTH_REFRESH_TOKEN'));
            $zohoClient->setZohoClientId($entity->companies->get('ZOHO_CLIENT_ID'));
            $zohoClient->setZohoClientSecret($entity->companies->get('ZOHO_CLIENT_SECRET'));

            $refresh = $zohoClient->manageAccessTokenRedis($di->get('redis'), 'zoho_client' . $companyId);
            $zohoClient->setModule('Leads');

            $request = [
                'First_Name' => $entity->firstname,
                'Last_Name' => $entity->lastname,
                'Lead_Source' => $entity->leads_receivers ? $entity->leads_receivers->name : '',
                'Phone' => $entity->phone,
                'Email' => $entity->email,
            ];

            $di->get('log')->info('Data lead', $request);

            $response = $zohoClient->insertRecords(
                $request,
                ['wfTrigger' => 'true']
            );
            $response = $response->getResponse();
            if (!empty($response['recordId'])) {
                $entity->saveLinkedSources($response);
            }
            $di->get('log')->info('Process Leads For company ' . $companyId, [$response]);
        } catch (Throwable $e) {
            $di->get('log')->error('Error processing lead - ' . $e->getMessage(), [$e->getTraceAsString()]);
        }

        return true;
    }
}
