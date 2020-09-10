<?php

namespace Kanvas\Packages\MobilePayments\Contracts;

class AppleReceipts implements ReceiptsInterface
{
    /**
     * Parse Receipt Data
     *
     * @param array $receiptData
     *
     * @return array
     */
    public function parse(array $receiptData): array
    {
        return [
            "is_mobile" => true,
            "receipt_creation_date" => gmdate('Y-m-d H:i:s', (int) $receiptData['receipt_creation_date_ms'] / 1000),
            "paid_status" => !empty(array_key_exists('transaction_id', current($receiptData['in_app']))) ? 1 : 0,
            "subscription_status" => !empty(array_key_exists('transaction_id', current($receiptData['in_app']))) ? 1 : 0
        ];
    }
}
