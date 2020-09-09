<?php

namespace Kanvas\Packages\Tests\Unit\MobilePayments;

use Kanvas\Packages\MobilePayments\Contracts\ReceiptValidatorTrait;
use Kanvas\Packages\MobilePayments\Contracts\AppleReceipts;
use UnitTester;

class ReceiptValidatorTraitCest
{
    /**
     * Receipt Validator Trait.
     */
    use ReceiptValidatorTrait;

    /**
     * Test to get HashtagFromStrings
     *
     * @param UnitTester $I
     * @return void
     */
    public function parseReceiptDataApple(UnitTester $I): void
    {
        $receipt = [
            "receipt_creation_date_ms" => gmdate('Y-m-d H:i:s', (int) 1532540395000 / 1000),
            "in_app" => [["transaction_id" => 21000048610733]]
        ];

        $formattedReceipt = $this->parseReceiptData($receipt, 'apple');

        $I->assertContains('is_mobile', $formattedReceipt);
        $I->assertContains('receipt_creation_date', $formattedReceipt);
        $I->assertContains('paid_status', $formattedReceipt);
        $I->assertContains('subscription_status', $formattedReceipt);
    }
}
