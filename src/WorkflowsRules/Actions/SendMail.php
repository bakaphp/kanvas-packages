<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Baka\Mail\Manager as BakaMail;
use Baka\Mail\Message;
use Canvas\Template;
use Kanvas\Packages\WorkflowsRules\Actions;
use Kanvas\Packages\WorkflowsRules\Contracts\WorkflowsEntityInterfaces;
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
        $args = $entity->getRulesRelatedEntities();

        if (!isset($entity->companies)) {
            $this->setStatus(Actions::FAIL);
            $this->setError(
                'No company relationship or No SMTP configuration pass for the current company'
            );

            return;
        }

        try {
            $params = $this->params;
            $data = $this->getModelsInArray(...$args);
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
        $company = $entity->getCompanies();

        $config = [
            'driver' => 'smtp',
            'host' => $company->get('EMAIL_HOST'),
            'port' => $company->get('EMAIL_PORT'),
            'username' => $company->get('EMAIL_USER'),
            'password' => $company->get('EMAIL_PASS'),
            'from' => [
                'email' => $company->get('EMAIL_FROM_PRODUCTION'),
                'name' => $company->get('EMAIL_FROM_NAME_PRODUCTION'),
            ],
            'debug' => [
                'from' => [
                    'email' => $company->get('EMAIL_FROM_DEBUG'),
                    'name' => $company->get('EMAIL_FROM_NAME_DEBUG'),
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
