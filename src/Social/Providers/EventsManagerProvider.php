<?php

namespace Kanvas\Packages\Social\Providers;

use Kanvas\Packages\Social\EventsManager;
use Phalcon\Di\ServiceProviderInterface;
use Phalcon\DiInterface;

class EventsManagerProvider implements ServiceProviderInterface
{
    /**
     * List of the listeners use by the app.
     *
     * [
     *  'eventName' => 'className'
     * ];
     *
     * @var array
     */
    protected $listeners = [
    ];

    /**
     * @param DiInterface $container
     */
    public function register(DiInterface $container)
    {
        if (!$container->has('events')) {
            $container->setShared(
                'events',
                function () {
                    $eventsManager = new EventsManager();
    
                    return $eventsManager;
                }
            );
        }

        $this->attachEvents($container, $this->listeners);
    }

    /**
     * given the DI attach the list of containers.
     *
     * @param DiInterface $container
     * @param array $listeners
     * @return void
     */
    protected function attachEvents(DiInterface $container, array $listeners): bool
    {
        if (empty($listeners)) {
            return false;
        }
        $eventsManager = $container->get('eventsManager');

        //navigate the list of listener and create the events
        foreach ($listeners as $key => $listen) {
            //create the events given the key
            $eventsManager->attach(
                $key,
                new $listen()
            );
        }

        return true;
    }
}
