<?php

declare(strict_types=1);

namespace Kanvas\Packages\Social\Traits\Topics;

use Kanvas\Packages\Social\Models\Topics;

/**
 * Topics Controller Trait.
 */
trait TopicableController
{
    /*
    * fields we accept to create
    *
    * @var array
    */
    protected $createFields = [
    ];

    /**
     * fields we accept to update.
     *
     * @var array
     */
    protected $updateFields = [
    ];

    /**
     * set objects.
     *
     * @return void
     */
    public function onConstruct()
    {
        $this->model = new Topics();

        $this->model->apps_id = $this->app->getId();
        $this->model->companies_id = $this->userData->getDefaultCompany()->getId();

        $this->additionalSearchFields = [
            ['apps_id', ':', $this->app->getId()],
            ['companies_id', ':', $this->userData->getDefaultCompany()->getId()],
            ['is_deleted', ':', 0],
        ];
    }
}
