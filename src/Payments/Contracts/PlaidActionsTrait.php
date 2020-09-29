<?php
declare(strict_types=1);

namespace Kanvas\Packages\Payments\Contracts;

use function Baka\envValue;
use Canvas\Validation as CanvasValidation;
use Kanvas\Packages\Payments\Exception\PaymentException;
use Phalcon\Http\Response;
use Phalcon\Validation\Validator\PresenceOf;
use Stripe\Charge;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\InvalidRequestException;
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
        $request = $this->request->getPostData();

        $validation = new CanvasValidation();
        $validation->add('public_token', new PresenceOf(['message' => _('The access_token is required.')]));
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
        $validation->add('public_token', new PresenceOf(['message' => _('The public_token is required.')]));
        $validation->add('account_id', new PresenceOf(['message' => _('The account_id is required.')]));
        $validation->add('amount', new PresenceOf(['message' => _('The amount is required.')]));
        $validation->validate($request);

        try {
            // here we get the access token
            $accessToken = $this->plaid->exchangeToken($request['public_token']);
            // here we get the stripe token with plaid
            $stripeToken = $this->plaid->createStripeToken($accessToken->access_token, $request['account_id']);
            // here we make the stripe charge
            Stripe::setApiKey(envValue('STRIPE_SECRET'));
            $charge = Charge::create([
                'amount' => str_replace('.', '', $request['amount']),
                'currency' => 'USD',
                'description' => $request['reference'],
                'source' => $stripeToken->stripe_bank_account_token
            ]);
        } catch (PlaidRequestException $e) {
            $this->log->error('Plaid ' . $e->getMessage());
            throw new PaymentException('We have a problem with plaid account');
        } catch (AuthenticationException $e) {
            throw new PaymentException('We have a problem with stripe auth');
        } catch (InvalidRequestException $e) {
            throw new PaymentException('We have a problem with stripe api');
        } catch (PaymentException $e) {
            throw new PaymentException('We have a problem processing');
        }
        return $this->response($charge);
    }
}
