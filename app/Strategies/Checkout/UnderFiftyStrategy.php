<?php

namespace App\Strategies\Checkout;

use App\Enums\CheckoutAmountEnum;
use App\Enums\DeliveryFeesEnum;

class UnderFiftyStrategy implements DeliveryCostStrategyInterface
{
    /**
     * @param float $amount
     * @return float
     */
    public function calculateCost(float $amount): float
    {
        return $amount < CheckoutAmountEnum::FIFTY->value ? (float)DeliveryFeesEnum::UNDER_FIFTY->value : 0;
    }
}

