<?php

namespace App\Services\PaymentGateway;

/**
 * The PaymentGateway interface declares operations common to all supported versions
 * of some algorithm.
 *
 * The Context uses this interface to call the algorithm defined by Concrete
 * Strategies.
 */
interface PaymentGateway
{
    public function charges(array $data): array;
}
