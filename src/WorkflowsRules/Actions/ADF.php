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
            $message = $entity->getMessage();
            $lead = $entity->entity();

            $vehicle = $message['data']['form'];
            $request = [
                'vehicle' => [
                    'year' => $vehicle['year'],
                    'model' => $vehicle['model'],
                    'make' => $vehicle['make'],
                    'vin' => $vehicle['vin'],
                    'trim' => $vehicle['trim'],
                    'transmission' => $vehicle['trans'] == 'Automatic' ? 'A' : 'M',
                    'bodystyle' => $vehicle['body_style'],
                    'mileage' => (int)$vehicle['mileage'] ?? 0,
                    'interiorColor' => $vehicle['int_color'],
                    'exteriorColor' => $vehicle['ext_color'],
                ],
                'customer' => [
                    'firstname' => $lead->firstname,
                    'lastname' => $lead->lastname,
                    'email' => $lead->email
                ],
                'vendor' => [
                    'firstname' => $lead->firstname,
                    'lastname' => $lead->lastname,
                    'email' => $lead->email
                ]
            ];
            $this->data = $request;
            $client = new Client();
            $url = getenv('URL_ADF_ENDPOINT');
            $response = $client->request('POST', $url, [
                'headers' => [
                    'Companies-Id' => $lead->companies_id
                ],
                'json' => $request
            ]);
            $body = $response->getBody();
            if ($response->getStatusCode() != 200) {
                $this->status = 0;
            }
            $this->message = $body;
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
