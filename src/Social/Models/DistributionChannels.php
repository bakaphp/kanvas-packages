<?php

namespace Kanvas\Packages\Social\Models;

class DistributionChannels extends BaseModel
{
    public $id;
    public $channel;
    public $queues;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'distribution_channels';
    }
}
