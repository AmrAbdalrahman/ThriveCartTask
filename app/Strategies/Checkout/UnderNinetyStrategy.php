<?php

namespace App\Strategies\Checkout;

use App\Enums\CheckoutAmountEnum;
use App\Enums\DeliveryFeesEnum;

class UnderNinetyStrategy implements DeliveryCostStrategyInterface
{
    /**
     * @param float $amount
     * @return float
     */
    public function calculateCost(float $amount): float
    {
        return $amount < CheckoutAmountEnum::NINETY->value ? (float)DeliveryFeesEnum::UNDER_NINETY->value : 0;
    }
}

