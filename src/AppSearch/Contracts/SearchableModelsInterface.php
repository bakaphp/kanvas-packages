<?php

declare(strict_types=1);

namespace Kanvas\Packages\AppSearch\Contracts;

interface SearchableModelsInterface
{
    /**
     * Define engine index name.
     *
     * @return string
     */
    public function getAppEngineName() : string;

    /**
     * After save send to index.
     *
     * @return void
     */
    public function searchEngineIndexDocument() : void;
}
