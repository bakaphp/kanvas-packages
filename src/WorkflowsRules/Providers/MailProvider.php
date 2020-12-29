<?php

declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Providers;

use function Baka\envValue;
use Baka\Mail\Manager as BakaMail;
use Phalcon\Di\DiInterface;
use Phalcon\Di\ServiceProviderInterface;

class MailProvider implements ServiceProviderInterface
{
    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container) : void
    {
        $container->setShared(
            'mail',
            function () {
                $config = [
                    'driver' => 'smtp',
                    'host' => envValue('WORKFLOW_EMAIL_HOST'),
                    'port' => envValue('WORKFLOW_EMAIL_PORT'),
                    'username' => envValue('WORKFLOW_EMAIL_USER'),
                    'password' => envValue('WORKFLOW_EMAIL_PASS'),
                    'from' => [
                        'email' => envValue('WORKFLOW_EMAIL_FROM_PRODUCTION'),
                        'name' => envValue('WORKFLOW_EMAIL_FROM_NAME_PRODUCTION'),
                    ],
                    'debug' => [
                        'from' => [
                            'email' => envValue('WORKFLOW_EMAIL_FROM_DEBUG'),
                            'name' => envValue('WORKFLOW_EMAIL_FROM_NAME_DEBUG'),
                        ],
                    ],
                ];
                $mailer = new BakaMail($config);
                return $mailer->createMessage();
            }
        );
    }
}
