<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Baka\Mail\Manager as BakaMail;
use Baka\Mail\Message;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\ActionInterfaces;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Phalcon\Di;
use Throwable ;

class SendMail implements ActionInterfaces
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
            $this->data = $entity->toArray();
            $template = get_class($di->get('templates'))::generate($params['template_name'], ['entity' => $entity]);
            $mail = $this->mailService($entity);
            $this->message = $template;
            $mail->to($params['toEmail'])
                ->from($params['fromEmail'])
                ->subject($params['subject'])
                ->content($template)
                ->sendNow();
        } catch (Throwable  $e) {
            $this->message = 'Error processing mail - ' . $e->getMessage();
            $di->get('log')->error('Error processing mail - ' . $e->getMessage(), [$e->getTraceAsString()]);
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
     * mailService.
     *
     * @param  WorkflowsEntityInterfaces $entity
     *
     * @return BakaMail
     */
    private function mailService(WorkflowsEntityInterfaces $entity) : Message
    {
        $config = [
            'driver' => 'smtp',
            'host' => $entity->getCompanies()->get('EMAIL_HOST'),
            'port' => $entity->getCompanies()->get('EMAIL_PORT'),
            'username' => $entity->getCompanies()->get('EMAIL_USER'),
            'password' => $entity->getCompanies()->get('EMAIL_PASS'),
            'from' => [
                'email' => $entity->getCompanies()->get('EMAIL_FROM_PRODUCTION'),
                'name' => $entity->getCompanies()->get('EMAIL_FROM_NAME_PRODUCTION'),
            ],
            'debug' => [
                'from' => [
                    'email' => $entity->getCompanies()->get('EMAIL_FROM_DEBUG'),
                    'name' => $entity->getCompanies()->get('EMAIL_FROM_NAME_DEBUG'),
                ],
            ],
        ];
        $mailer = new BakaMail($config);
        return $mailer->createMessage();
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
