<?php
declare(strict_types=1);

namespace Kanvas\Packages\Recommendations\Contracts;

use Recombee\RecommApi\Client;

interface Engine
{
    public static function getInstance(Database $database) : Engine;
    public function connect() : Client;
    public function database() : Database;
    public function interactions() : Interactions;
    public function items() : Items;
    public function recommendation() : Recommendation;
}
