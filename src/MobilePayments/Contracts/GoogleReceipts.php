<?php

namespace Kanvas\Packages\MobilePayments\Contracts;

class GoogleReceipts implements ReceiptsInterface
{
    /**
     * Parse Receipt Data.
     *
     * @param array $receiptData
     *
     * @return array
     */
    public function parse(array $receiptData) : array
    {
        return [];
    }
}
