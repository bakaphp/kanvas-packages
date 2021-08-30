<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation\Contracts;

interface Items
{
    public function add() : bool;
    public function addMultiple() : bool;
    public function delete() : bool;
    public function list() : bool;
}
