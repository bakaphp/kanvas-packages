<?php

declare(strict_types=1);

namespace Kanvas\Packages\MobilePayments\Contracts;

use ReceiptValidator\iTunes\Validator as iTunesValidator;
use Phalcon\Http\Response;

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
     * @param string $receiptData
     * @param string $source
     * @return Response
     */
    public function validateReceipt(string $receiptData): ?array
    {
        $validator = new iTunesValidator(iTunesValidator::ENDPOINT_PRODUCTION); // Or iTunesValidator::ENDPOINT_SANDBOX if sandbox testing
        $sharedSecret = getenv('ITUNES_STORE_PASS');

        try {
            $response = $validator->setSharedSecret($sharedSecret)->setReceiptData($receiptData)->validate(); // use setSharedSecret() if for recurring subscriptions
        } catch (Throwable $e) {
            throw new Throwable($e->getMessage());
        }

        return $response->isValid() ? $response->getReceipt() : 'Receipt result code = ' . $response->getResultCode();
    }

    /**
     * Parses receipt data depending of the source
     *
     * @param array $receiptData
     * @param string $source
     *
     * @return array
     */
    public function parseReceiptData(array $receiptData, string $source): array
    {
        switch ($source) {
            case 'apple':
                $appleReceipt = new AppleReceipts();
                return $appleReceipt->parse($receiptData);
                break;

            case 'google':
                $googleReceipt = new GoogleReceipts();
                return $googleReceipt->parse($receiptData);
                break;
            default:
                return [];
                break;
        }
    }
}
