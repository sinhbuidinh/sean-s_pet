<?php

namespace App\Enum;

use App\Services\PaymentGateway\PaymentGateway;
use App\Services\PaymentGateway\StripePayment;

class GatewayEnum
{
    const STRIPE = 1;

    public static function getAll(): array
    {
        return array_values((new \ReflectionClass(static::class))->getConstants());
    }

    public static function getDisplay(): array
    {
        return [
            self::STRIPE => 'STRIPE',
        ];
    }

    public static function mapEnumToGateway(int $enum): PaymentGateway
    {
        return match ($enum) {
            self::STRIPE => app(StripePayment::class),
        };
    }
}
