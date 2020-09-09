<?php

declare(strict_types=1);

namespace Kanvas\Packages\AppSearch\Contracts;

use Baka\Constants\Flags;
use Baka\getShortClassName;
use function Baka\getShortClassName;
use Kanvas\Packages\AppSearch\Jobs\IndexAppSearchModels;
use Phalcon\Di;

trait SearchableModelsTrait
{
    /**
     * Get the name of the app engine search
     * AKA this is the index in elastic search.
     *
     * @return string
     */
    public function getAppEngineName() : string
    {
        return strtolower(getShortClassName($this));
    }

    /**
     * Index the document.
     *
     * @return void
     */
    public function searchEngineIndexDocument() : void
    {
        if (Di::getDefault()->get('config')->app->env == Flags::PRODUCTION) {
            IndexAppSearchModels::dispatch($this);
        }
    }
}
