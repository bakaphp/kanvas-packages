<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Phalcon\Di;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Throwable;
use Weble\ZohoClient\Enums\Region;
use Weble\ZohoClient\OAuthClient;
use Webleit\ZohoCrmApi\Client;
use Webleit\ZohoCrmApi\Enums\Mode;
use Webleit\ZohoCrmApi\ZohoCrm;
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

            $zohoClient = new ZohoClient();

            $zohoClientId = $entity->getCompanies()->get('ZOHO_CLIENT_ID');
            $zohoSecret = $entity->getCompanies()->get('ZOHO_CLIENT_SECRET');
            $zohoRefreshToken = $entity->getCompanies()->get('ZOHO_AUTH_REFRESH_TOKEN');

            $redis = RedisAdapter::createConnection(
                'redis://' . $di->get('config')->cache['options']['redis']['host']
            );

            $cache = new RedisAdapter(
                $redis,
                $namespace = '',
                $defaultLifetime = 0
            );

            // setup the generic zoho oath client
            $oAuthClient = new OAuthClient($zohoClientId, $zohoSecret);
            $oAuthClient->setRefreshToken($zohoRefreshToken);
            $oAuthClient->setRegion(Region::us());
            $oAuthClient->useCache($cache);
            $oAuthClient->offlineMode();

            // setup the zoho crm client
            $client = new Client($oAuthClient);
            $client->setMode(Mode::production());

            // Create the main class
            $zohoCrm = new ZohoCrm($client);

            $request = [
                'First_Name' => $entity->firstname,
                'Last_Name' => $entity->lastname,
                //    'Lead_Source' => $entity->leads_receivers ? $entity->leads_receivers->name : '',
                'Phone' => $entity->phone,
                'Email' => $entity->email,
            ];
            $customFields = method_exists($entity, 'getAll') ? $entity->getAll() : [];
            $request = array_merge($customFields, $request);

            $zohoLead = $zohoCrm->leads->create($request);
            $zohoId = $zohoLead->getId();

            if (method_exists($entity, 'set')) {
                $entity->set('zoho_id', $zohoLead->getId());
            }

            $this->data = $request;
            $this->message = 'Process Leads For company ' . $companyId . ' - Zoho Id' . $zohoId;
            $this->status = 1;
        } catch (Throwable $e) {
            $this->message = 'Error processing lead - ' . $e->getMessage();
            $this->status = 0;
            $response = $e->getTraceAsString();
        }

        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
            'zohoId' => isset($zohoLead) ? $zohoLead->getId() : 0,
            'body' => $response
        ];
    }
}
