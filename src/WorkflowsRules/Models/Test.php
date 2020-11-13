<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;

class Test implements WorkflowsEntityInterfaces
{
    public ?string $name = null;
    public ?string $city = null;
    public string $firstname;
    public string $lastname;
    public string $phone;
    public string $email;
    public ?string $leads_receivers = null;
    public ?int $companies_id = null;
    public string $dob;

    /**
     * toArray.
     *
     * @return array
     */
    public function toArray() : array
    {
        return get_object_vars($this);
    }

    /**
     * getAll.
     *
     * @return array
     */
    public function getAll() : array
    {
        return [];
    }

    /**
     * getCompanies.
     *
     * @return Companies
     */
    public function getCompanies() : Companies
    {
        return new Companies();
    }

    public function saveLinkedSources($variable)
    {
    }
}
