<?php

namespace App\Services\PaymentGateway;

use App\Services\PaymentGateway\PaymentGateway;
use Stripe\Exception\CardException;
use Stripe\StripeClient;
use Exception;

class StripePayment implements PaymentGateway
{
    private $stripe;

    protected function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.api_keys.secret_key'));
    }

    public function createToken(array $cardData)
    {
        $token = null;
        try {
            $token = $this->stripe->tokens->create([
                'card' => [
                    'number' => $cardData['cardNumber'],
                    'exp_month' => $cardData['month'],
                    'exp_year' => $cardData['year'],
                    'cvc' => $cardData['cvv'],
                ],
            ]);
        } catch (CardException $e) {
            $token['error'] = $e->getError()->message;
        } catch (Exception $e) {
            $token['error'] = $e->getMessage();
        }

        return $token;
    }

    public function createCharge(string $tokenId, $amount)
    {
        $charge = null;
        try {
            $charge = $this->stripe->charges->create([
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $tokenId,
                'description' => 'My first payment',
            ]);
        } catch (Exception $e) {
            $charge['error'] = $e->getMessage();
        }

        return $charge;
    }

    public function charges(array $data): array
    {
        $token = $this->createToken($data);
        if (!empty($token['error'])) {
            return ['danger', $token['error'] ?? 'Error occur'];
        }

        if (empty($token['id'])) {
            return ['danger', 'Payment failed'];
        }

        $tokenId = $token['id'];
        $amount = $data['amount'] ?? 2;
        $charge = $this->createCharge($tokenId, $amount);
        if (!empty($charge) && $charge['status'] == 'succeeded') {
            return ['success', 'Payment completed.'];
        }

        return ['danger', 'Payment failed.'];
    }
}
