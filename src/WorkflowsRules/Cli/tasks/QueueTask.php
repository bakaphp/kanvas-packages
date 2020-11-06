<?php

use Baka\Contracts\Queue\QueueableJobInterface;
use Baka\Queue\Queue;
use Phalcon\Cli\Task as PhTask;

class QueueTask extends PhTask
{
    /**
     * Queue action for mobile notifications.
     *
     * @return void
     */
    public function mainAction() : void
    {
        echo 'Canvas Ecosystem Queue Jobs: events | notifications | jobs' . PHP_EOL;
    }

    /**
     * Queue to process Canvas Jobs.
     *
     * @return void
     */
    public function jobsAction(?string $queueName = null)
    {
        $queue = is_null($queueName) ? QUEUE::JOBS : $queueName;

        $callback = function ($msg) {
            //check the db before running anything
            if (!$this->isDbConnected('dbWorkflow')) {
                return ;
            }
            //we get the data from our event trigger and unserialize
            $job = unserialize($msg->body);
            //overwrite the user who is running this process
            if ($job['userData'] instanceof Users) {
                $this->di->setShared('userData', $job['userData']);
            }

            if (!class_exists($job['class'])) {
                echo 'No Job class found' . PHP_EOL;
                $this->log->error('No Job class found ' . $job['class']);
                return;
            }

            if (!$job['job'] instanceof QueueableJobInterface) {
                echo 'This Job is not queueable ' . $msg->delivery_info['consumer_tag'] ;
                $this->log->error('This Job is not queueable ' . $msg->delivery_info['consumer_tag']);
                return;
            }

            try {
                /**
                 * swoole coroutine.
                 */
                go(function () use ($job, $msg) {
                    //instance notification and pass the entity
                    try {
                        $result = $job['job']->handle();
                    } catch (Throwable $e) {
                        $this->log->info(
                            $e->getMessage(),
                            [$e->getTraceAsString()]
                        );
                    }
                });
            } catch (Throwable $e) {
                $this->log->info(
                    $e->getMessage(),
                    [$e->getTraceAsString()]
                );
            }
        };

        Queue::process($queue, $callback);
    }

    /**
     * Confirm if the db is connected.
     *
     * @return bool
     */
    protected function isDbConnected(string $dbProvider) : bool
    {
        try {
            $this->di->get($dbProvider)->fetchAll('SELECT 1');
        } catch (Throwable $e) {
            if (strpos($e->getMessage(), 'MySQL server has gone away') !== false) {
                $this->di->get($dbProvider)->connect();
                return true;
            }
            return false;
        }
        return true;
    }
}
