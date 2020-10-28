<?php
declare(strict_types=1);

namespace Kanvas\Packages\WorkflowsRules\Models;

class Test
{
    public ?string $name = null;
    public ?string $city = null;
    public string $firstname;
    public string $lastname;
    public string $phone;
    public string $email;
    public ?string $leads_receivers = null;

    /**
     * toArray.
     *
     * @return array
     */
    public function toArray() : array
    {
        return get_object_vars($this);
    }
}
