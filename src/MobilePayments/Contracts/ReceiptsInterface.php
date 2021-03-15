<?php

namespace Kanvas\Packages\MobilePayments\Contracts;

interface ReceiptsInterface
{
    /**
     * Parse Receipt Data.
     *
     * @param array $receiptData
     *
     * @return array
     */
    public function parse(array $receiptData) : array;
}
