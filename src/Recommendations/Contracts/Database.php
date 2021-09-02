<?php

namespace Kanvas\Packages\Recommendation\Contracts;

interface Recomendation
{
    public function create();
    public function delete();
    public function getSource();
}
