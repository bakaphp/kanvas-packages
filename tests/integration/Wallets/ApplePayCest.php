<?php

namespace Kanvas\Packages\Tests\Integration\Wallets;

use IntegrationTester;
use Kanvas\Packages\MobilePayments\Contracts\ReceiptValidatorTrait;
use Kanvas\Packages\Wallets\PaymentMethods\ApplePay;

class ApplePayCest
{
    public ChannelsModel $channel;

    /**
     * Validate Apple Pay receipts
     *
     * @param string $receiptData
     * @param string $source
     * @return array
     */
    public function validatePaymentTest(UnitTester $I): void
    {
        $receiptData = getenv('ITUNES_RECEIPT_EXAMPLE');
        $sharedSecret = getenv('ITUNES_STORE_PASS');
        $user = new Users();

        $applePay = new ApplePay();

        $response = $applePay->validatePayment($receipt);

        $I->assertIsArray($response);
    }
}
