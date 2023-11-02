<?php

namespace App\Http\Services;

use App\Services\PaymentGateway\PaymentGateway;

class PaymentService
{
     /**
     * @var PaymentGateway The Context maintains a reference to one of the Strategy
     * objects. The Context does not know the concrete class of a strategy. It
     * should work with all strategies via the Strategy interface.
     */
    private $gateway;

    /**
     * Usually, the Context accepts a strategy through the constructor, but also
     * provides a setter to change it at runtime.
     */
    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * The Context delegates some work to the Strategy object instead of
     * implementing multiple versions of the algorithm on its own.
     * 
     * With payment
     *    + create token
     *    + validate & return result
     *    + charge & return result
     * 
     * @return array [$color, $message] $color: color for display flash message, $message: message detail
     */
    public function charges(array $formData): array
    {
        return $this->gateway->charges($formData);
    }
}
