<?php

namespace Kanvas\Packages\Recommendation\Contracts;

interface Database
{
    public function create();
    public function delete();
    public function getSource();
}
