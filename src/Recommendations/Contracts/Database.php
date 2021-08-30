<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendation\Contracts;

interface Database
{
    public function create() : bool;
    public function delete() : bool;
    public function getSource() : string;
}
