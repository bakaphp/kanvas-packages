<?php

namespace Kanvas\Packages\Recommendation\Contracts;

interface Items
{
    public function add();
    public function addMultiple();
    public function delete();
    public function list();
}