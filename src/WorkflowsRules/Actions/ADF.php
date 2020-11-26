<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use GuzzleHttp\Client;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Phalcon\Di;
use Throwable;

class ADF extends Action
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
        $di = Di::getDefault();
        try {
            $data = $entity->getAll();
            $request = [
                'name' => $data['firstname'],
                'lastname' => $data['lastname'],
                'phone' => $data['phone'],
                'message' => $data['message'],
                'email' => $data['email'],
                'username' => $data['username'] ?: null,
                'vehicleid' => $data['vehicleid'] ?: null,
                'rooftopid' => $data['rooftopid'] ?: null,
                'dealergroupid' => $data['dealergroupid'] ?: null
            ];
            $client = new Client();
            $baseUrl = getenv('URL_DEALER_API');
            $response = $client->request('POST', "{$baseUrl}/forms/vehicleinterest", [
                'json' => $request
            ]);
            $body = $response->getBody();
            if ($response->getStatusCode() != 200) {
                $this->status = 0;
            }
            $this->message = $body;
            $this->data = $request;
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
