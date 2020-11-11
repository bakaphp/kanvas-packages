<?php

namespace Kanvas\Packages\WorkflowsRules\Actions;

use Baka\Mail\Manager as BakaMail;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\ActionInterfaces;

class SendMail implements ActionInterfaces
{
    private $mailManager;

    /**
     * handle.
     *
     * @param  object $entity
     * @param  array $params
     *
     * @return void
     */
    public function handle(object $entity, array $params = []) : void
    {
        $this->createMailManager($entity);
        foreach ($params as $mail) {
            $this->mailManager->to($mail)
            ->subject('Test Normal Email now')
            ->content('send normal email now')
            ->send();
        }
    }

    /**
     * createMailManager.
     *
     * @param  object $entity
     *
     * @return void
     */
    public function createMailManager(object $entity) : void
    {
        $config = [
            'driver' => 'smtp',
            'host' => $entity->companies->get('EMAIL_HOST'),
            'port' => $entity->companies->get('EMAIL_PORT'),
            'username' => $entity->companies->get('EMAIL_USER'),
            'password' => $entity->companies->get('EMAIL_PASS'),
            'from' => [
                'email' => $entity->companies->get('EMAIL_FROM_PRODUCTION'),
                'name' => $entity->companies->get('EMAIL_FROM_NAME_PRODUCTION'),
            ],
            'debug' => [
                'from' => [
                    'email' => $entity->companies->get('EMAIL_FROM_DEBUG'),
                    'name' => $entity->companies->get('EMAIL_FROM_NAME_DEBUG'),
                ],
            ],
        ];
        $this->mailManager = (new BakaMail($config))->createMessage();
    }
}
