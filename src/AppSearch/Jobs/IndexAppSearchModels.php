<?php

declare(strict_types=1);

namespace Kanvas\Packages\AppSearch\Jobs;

use Baka\Contracts\Queue\QueueableJobInterface;
use Baka\Jobs\Job;
use Exception;
use Gewaer\Models\Storms;
use Kanvas\Packages\AppSearch\Contract\SearchableModelsInterface;
use Phalcon\Di;

class IndexAppSearchModels extends Job implements QueueableJobInterface
{
    protected $entity;

    /**
     * Constructor.
     *
     * @param Storms $storm
     */
    public function __construct(SearchableModelsInterface $entity)
    {
        $this->entity = $entity;

        if (!Di::getDefault()->has('elasticApp')) {
            throw new Exception('Please configure the elasticApp Provider');
        }
    }

    /**
     * Handle the index of the document.
     *
     * @return void
     */
    public function handle()
    {
        $elastic = Di::getDefault()->get('elasticApp');
        $engine = $this->entity->getAppEngineName();

        //check engine
        if ($elastic->getEngine($engine)) {
            //index document
            $elastic->indexDocuments(
                $engine,
                [
                    $this->entity->toArray()
                ]
            );

            Di::getDefault()->get('log')->info(
                'Index Entity ' . get_class($this->entity) . ' ' . $this->entity->getId()
            );

            return true;
        }

        return false;
    }
}
