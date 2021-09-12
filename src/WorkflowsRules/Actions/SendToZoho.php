<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Kanvas\Packages\WorkflowsRules\Actions;
use Kanvas\Packages\WorkflowsRules\Contracts\WorkflowsEntityInterfaces;
use Phalcon\Di;
use Throwable;
use Zoho\CRM\ZohoClient;

class SendToZoho extends Actions
{
    /**
     * handle.
     *
     * @param WorkflowsEntityInterfaces $entity
     * @param array $params
     *
     * @return void
     */
    public function handle(WorkflowsEntityInterfaces $entity) : void
    {
        $response = null;
        try {
            $this->setStatus(Actions::SUCCESSFUL);
            $this->setResults([
                'request' => [],
                'response' => []
            ]);
            return;

            $di = Di::getDefault();
            $companyId = $entity->companies_id;
            $args = $entity->getRulesRelatedEntities();

            $di->get('log')->info('Start Process Leads For company ' . $companyId);

            $zohoClient = new ZohoClient();

            ///get from db
            $zohoClient->setAuthRefreshToken(
                $entity->getCompanies()->get('ZOHO_AUTH_REFRESH_TOKEN')
            );
            $zohoClient->setZohoClientId(
                $entity->getCompanies()->get('ZOHO_CLIENT_ID')
            );
            $zohoClient->setZohoClientSecret(
                $entity->getCompanies()->get('ZOHO_CLIENT_SECRET')
            );

            $refresh = $zohoClient->manageAccessTokenRedis(
                $di->get('redis'),
                'zoho_client' . $companyId
            );
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

            unset($request['Code'], $request['Programs'], $request['Office'], $request['Business_Founded']);
            if (isset($request['Retirement_Account'])) {
                $request['Retirement_Account'] = [$request['Retirement_Account']];
            }
            if (isset($request['Available_Collateral'])) {
                $request['Available_Collateral'] = [$request['Available_Collateral']];
            }

            $di->get('log')->info('Data lead', $request);

            $response = $zohoClient->insertRecords(
                $request,
                ['wfTrigger' => 'true']
            );
            $response = $response->getResponse();
            if (!empty($response['recordId'])) {
                $entity->saveLinkedSources($response);
            }

            $this->setResults([
                'request' => $request,
                'response' => $response
            ]);

            $this->setStatus(Actions::SUCCESSFUL);
            $di->get('log')->info('Process Leads For company ' . $companyId, [$response]);
        } catch (Throwable $e) {
            $this->setStatus(Actions::FAIL);
            $this->setError('Error processing Email - ' . $e->getMessage());
        }
    }
}
