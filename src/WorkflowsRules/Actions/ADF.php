<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use GuzzleHttp\Client;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\ActionInterfaces;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Phalcon\Di;
use Throwable;

class ADF implements ActionInterfaces
{
    protected ?string $message = null;

    protected ?array $data = [];

    protected int $status = 1;

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
                'username' => key_exists('username', $data) ? $data['username'] : null,
                'vehicleid' => key_exists('vehicleid', $data) ? $data['vehicleid'] : null,
                'rooftopid' => key_exists('rooftopid', $data) ? $data['rooftopid'] : null,
                'dealergroupid' => key_exists('dealergroupid', $data) ? $data['dealergroupid'] : null,
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

    /**
     * getData.
     *
     * @return array
     */
    public function getData() : ?array
    {
        return $this->data;
    }

    /**
     * getMessage.
     *
     * @return string
     */
    public function getMessage() : ?string
    {
        return $this->message;
    }

    /**
     * getStatus.
     *
     * @return bool
     */
    public function getStatus() : int
    {
        return $this->status;
    }
}
