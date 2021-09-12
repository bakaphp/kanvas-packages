<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Baka\Mail\Manager as BakaMail;
use Baka\Mail\Message;
use Canvas\Template;
use Kanvas\Packages\WorkflowsRules\Actions;
use Kanvas\Packages\WorkflowsRules\Contracts\WorkflowsEntityInterfaces;
use Phalcon\Di;
use Throwable;

class SendMail extends Actions
{
    protected ?string $message = null;
    protected ?array $data = [];
    protected int $status = 1;

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
        $di = Di::getDefault();
        $args = $entity->getRulesRelatedEntities();

        if (!isset($entity->companies)) {
            $this->setStatus(Actions::FAIL);
            $this->setResults([
                'No company relationship or No SMTP configuration pass for the current company'
            ]);
        }

        try {
            $params = $this->params;
            $data = $this->getModels(...$args);
            $data['entity'] = $entity;
            $template = Template::generate($this->params['template_name'], $data);

            $mail = $this->mailService($entity);
            $mail->to($params['toEmail'])
                ->from($params['fromEmail'])
                ->subject($params['subject'])
                ->content($template)
                ->sendNow();

            $this->setStatus(Actions::SUCCESSFUL);
            $this->setResults(['mail' => $template]);
        } catch (Throwable  $e) {
            $this->setStatus(Actions::FAIL);
            $this->setError('Error processing Email - ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
        }
    }

    /**
     * mailService.
     *
     * @param WorkflowsEntityInterfaces $entity
     *
     * @return Message
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
     * @return int
     */
    public function getStatus() : int
    {
        return $this->status;
    }
}
