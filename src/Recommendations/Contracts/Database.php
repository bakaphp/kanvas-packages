<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendations\Contracts;

interface Database
{
    public function create(Engine $engine, callable $fn) : bool;
    public function delete(Engine $engine, callable $fn) : bool;
    public function getSource() : string;
    public function getPassword() : string;
    public function getItemsType() : string;
}
