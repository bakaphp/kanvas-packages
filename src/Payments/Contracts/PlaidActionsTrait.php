<?php

namespace Kanvas\Packages\Payments\Contracts;

use function Baka\envValue;
use Canvas\Validation as CanvasValidation;
use Phalcon\Http\Response;
use Phalcon\Validation\Validator\PresenceOf;
use Stripe\Charge;
use Stripe\Stripe;
use TomorrowIdeas\Plaid\PlaidRequestException;

trait PlaidActionsTrait
{
    /**
     * plaidAuth function
     * get plaid account form access token and return ach numbers account.
     *
     * @return Response
     */
    public function plaidAuth() : Response
    {
        $validation = new CanvasValidation();
        $validation->add('public_token', new PresenceOf(['message' => _('The access_token is required.')]));
        $request = $this->request->getPostData();
        $validation->validate($request);
        $response = $this->plaid->exchangeToken($request['public_token']);
        $accessToken = $response->access_token;
        $auth = $this->plaid->getAuth($accessToken);
        if (!$auth->numbers || !$auth->numbers->ach) {
            throw new Exception('We have a problem on palid account');
        }
        return $this->response($auth->numbers->ach);
    }

    /**
     * plaidTransaction function
     * make a plaid charge through stripe.
     *
     * @return Response
     */
    public function plaidTransaction() : Response
    {
        $request = $this->request->getPostData();
        $validation = new CanvasValidation();
        $validation->add('public_token', new PresenceOf(['message' => _('The access_token is required.')]));
        $validation->add('account_id', new PresenceOf(['message' => _('The access_token is required.')]));
        $validation->add('amount', new PresenceOf(['message' => _('The access_token is required.')]));
        $validation->validate($request);

        $charge = $this->makeTransaction($request);
        return $this->response($charge);
    }

    /**
     * makeTransaction
     * make a plaid charge through stripe.
     *
     * @return Charge
     */
    private function makeTransaction($payment) : Charge
    {
        $plaid = DI::getDefault()->getPlaid();
        try {
            // here we get the access token
            $accessToken = $this->plaid->exchangeToken($payment['public_token']);

            // here we get the stripe token with plaid
            $stripeToken = $this->plaid->createStripeToken($accessToken->access_token, $payment['account_id']);
            // here we make the stripe charge
            Stripe::setApiKey(envValue('STRIPE_SECRET'));

            $charge = Charge::create([
                'amount' => str_replace('.', '', $payment['amount']),
                'currency' => 'USD',
                'description' => $payment['reference'],
                'source' => $stripeToken->stripe_bank_account_token
            ]);
        } catch (PlaidRequestException $e) {
            throw new Exception('We have a problem on palid account');
        }
        return $charge;
    }
}
