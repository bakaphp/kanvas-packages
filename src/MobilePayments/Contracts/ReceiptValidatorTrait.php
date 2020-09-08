<?php

declare(strict_types=1);

namespace Kanvas\Packages\MobilePayments\Contracts;

use ReceiptValidator\iTunes\Validator as iTunesValidator;

/**
 * Phalcon\Traits\ReceiptValidatorTrait.
 *
 * Trait for validating mobile payments receipts
 *
 * @package Phalcon\Traits
 */

trait ReceiptValidatorTrait
{
    /**
     * Validate Apple Pay receipts
     *
     * @return Response
     */
    public function validateApplePayReceipt(): Response
    {
        $request = $this->request->getPostData();
        $validator = new iTunesValidator(iTunesValidator::ENDPOINT_PRODUCTION); // Or iTunesValidator::ENDPOINT_SANDBOX if sandbox testing

        $receiptBase64Data = $request['receipt-data'];
        $sharedSecret = $request['password'];

        try {
            $response = $validator->setSharedSecret($sharedSecret)->setReceiptData($receiptBase64Data)->validate(); // use setSharedSecret() if for recurring subscriptions
        } catch (Throwable $e) {
            throw new Throwable($e->getMessage());
        }

        if ($response->isValid()) {
            return $this->response($response->getReceipt());
            $this->updateSubscriptionPaymentStatus($this->userData, $this->parseReceiptData($response->getReceipt(), 'apple'));
            return $this->response($response->getReceipt());
        } else {
            return $this->response('Receipt result code = ' . $response->getResultCode());
        }
    }

    /**
     * Parses receipt data depending of the source
     *
     * @param array $receiptData
     * @param string $source
     *
     * @return array
     */
    private function parseReceiptData(array $receiptData, string $source): array
    {
        switch ($source) {
            case 'apple':
                $appleReceipt = new AppleReceipts();
                return $appleReceipt->parse($receiptData);
                break;

            case 'apple':
                $googleReceipt = new AppleReceipts();
                return $googleReceipt->parse($receiptData);
                break;
            default:
                return [];
                break;
        }
    }
}
