<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Contracts\Feeds;

use Baka\Contracts\Http\Api\CrudElasticBehaviorTrait;
use Baka\Contracts\Http\Api\ResponseTrait;
use Kanvas\Packages\Social\ElasticDocuments\Messages as ElasticDocumentsMessages;

/**
 * Channels Trait.
 */
trait ChannelsTrait
{
    use CrudElasticBehaviorTrait;
    use ResponseTrait;

    /**
     * set objects.
     *
     * @return void
     */
    public function onConstruct() : void
    {
        $this->model = new ElasticDocumentsMessages();

        $this->additionalSearchFields = [
            $this->di->has('userData') ? ['companies_id', ':',  $this->userData->getDefaultCompany()->getId()] : ['companies_id', '!',   0],
            ['is_deleted', ':', 0],
        ];
    }
}
