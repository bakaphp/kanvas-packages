<?php

namespace Kanvas\Packages\Social\Models;

class DistributionChannels extends BaseModel
{
    public $id;
    public $channel;
    public $queues;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSource('distribution_channels');
    }
}
