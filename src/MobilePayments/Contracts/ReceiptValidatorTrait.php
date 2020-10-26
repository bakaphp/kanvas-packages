<?php

declare(strict_types=1);

namespace Kanvas\Packages\MobilePayments\Contracts;

use ReceiptValidator\iTunes\Validator as iTunesValidator;
use Phalcon\Http\Response;
use Canvas\Models\Users;
use Canvas\Models\Subscription;
use Canvas\Models\CompaniesSettings;
use Canvas\Exception\ServerErrorHttpException;

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
     * Updates subscription payment status depending on charge event.
     * @param $user
     * @param $payload
     * @return void
     */
    public function updateSubscriptionPaymentStatus(Users $user, array $payload): void
    {
        // Identify if payload comes from mobile payments
        if ($payload['is_mobile']) {
            $chargeDate = $payload['receipt_creation_date'];
            $paidStatus = $payload['paid_status'];
            $subscriptionStatus = $payload['subscription_status'];
        } else {
            $chargeDate = date('Y-m-d H:i:s', (int) $payload['data']['object']['created']);
            $paidStatus = $payload['data']['object']['paid'];
            $subscriptionStatus = $payload['data']['object']['status'];
        }

        //Fetch current user subscription
        $subscription = Subscription::getByDefaultCompany($user);

        if (is_object($subscription)) {
            $subscription->paid = $paidStatus ?? 0;
            $subscription->charge_date = $chargeDate;

            $subscription->validateByGracePeriod();

            if ($subscription->paid) {
                $subscription->is_freetrial = 0;
                $subscription->trial_ends_days = 0;
            }

            //Paid status is false if plan has been canceled
            if ($subscriptionStatus == 'canceled') {
                $subscription->paid = 0;
                $subscription->charge_date = null;
            }

            if ($subscription->updateOrFail()) {
                //Update companies setting
                $paidSetting = CompaniesSettings::findFirst([
                    'conditions' => "companies_id = ?0 and name = 'paid' and is_deleted = 0",
                    'bind' => [$user->getDefaultCompany()->getId()]
                ]);

                $paidSetting->value = (string) $subscription->paid;
                $paidSetting->updateOrFail();
            }
            $this->log->info("User with id: {$user->id} charged status was {$paidStatus} \n");
        } else {
            $this->log->error("Subscription not found\n");
        }
    }

    /**
     * Update subscription payment status via Mobile Payments
     *
     * @return Response
     */
    public function updateSubscriptionStatusMobilePayments(): Response
    {
        $request = $this->request->getPostData();
        $receipt = $this->validateReceipt($request['receipt-data']);

        if (gettype($receipt) == 'string') {
            throw new ServerErrorHttpException($receipt);
        }

        $this->updateSubscriptionPaymentStatus($this->userData, $this->parseReceiptData($receipt, "apple"));
        return $this->response($receipt);
    }


    /**
     * Validate Apple Pay receipts
     *
     * @param string $receiptData
     * @param string $source
     * @return array
     */
    public function validateReceipt(string $receiptData)
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
