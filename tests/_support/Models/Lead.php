<?php

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\Social\Contracts\Channels\ChannelsInterface;
use Kanvas\Packages\Social\Contracts\Channels\ChannelsTrait;
use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;
use Kanvas\Packages\WorkflowsRules\Contracts\Traits\RulesTrait;

class Lead extends BaseModel implements ChannelsInterface, WorkflowsEntityInterfaces
{
    use ChannelsTrait;
    use RulesTrait;

    public function initialize()
    {
        parent::initialize();
        $this->setSource('leads');

        $this->belongsTo(
            'companies_id',
            Companies::class,
            'id',
            [
                'alias' => 'companies'
            ]
        );
    }

    public function getId() : int
    {
        return $this->id;
    }
}
