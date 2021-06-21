<?php
declare(strict_types=1);

namespace Kanvas\Packages\Test\Support\Models;

use Kanvas\Packages\WorkflowsRules\Contracts\Interfaces\WorkflowsEntityInterfaces;

class TestRule implements WorkflowsEntityInterfaces
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
    public int $vehicleid = 1919;
    public int $rooftopid = 106;

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
        return $this->toArray();
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

    /**
     * saveLinkedSources.
     *
     * @param  mixed $variable
     *
     * @return void
     */
    public function saveLinkedSources($variable) : void
    {
    }

    /**
     * afterRules.
     *
     * @return void
     */
    public function afterRules() : void
    {
    }
}
