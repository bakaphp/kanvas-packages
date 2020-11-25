<?php

namespace Kanvas\Packages\Tests\Unit\MobilePayments;

use Kanvas\Packages\MobilePayments\Contracts\ReceiptValidatorTrait;
use UnitTester;

class ReceiptValidatorTraitCest
{
    /**
     * Validate Apple Pay receipts
     *
     * @param string $receiptData
     * @param string $source
     * @return array
     */
    public function validateReceiptTest(UnitTester $I): void
    {
        $receipt = getenv('ITUNES_RECEIPT_EXAMPLE');
        $sharedSecret = getenv('ITUNES_STORE_PASS');

        $receiptValidator = new class {
            use ReceiptValidatorTrait;
        };

        $response = $receiptValidator->validateReceipt($receipt);

        $I->assertIsArray($response);
    }

    /**
     * Test to parse Apple Receipt Data
     *
     * @param UnitTester $I
     * @return void
     */
    public function parseAppleReceiptData(UnitTester $I): void
    {
        $receipt = [
            "receipt_creation_date_ms" => gmdate('Y-m-d H:i:s', (int) 1532540395000 / 1000),
            "in_app" => [[
                "transaction_id" => 21000048610733,
                "product_id" => "example.product.id",
                "quantity" => 1
                ]]
        ];

        $receiptValidator = new class {
            use ReceiptValidatorTrait;
        };
        $formattedReceipt = $receiptValidator->parseReceiptData($receipt, 'apple');

        $I->assertArrayHasKey('is_mobile', $formattedReceipt);
        $I->assertArrayHasKey('receipt_creation_date', $formattedReceipt);
        $I->assertArrayHasKey('paid_status', $formattedReceipt);
        $I->assertArrayHasKey('subscription_status', $formattedReceipt);
    }
}
