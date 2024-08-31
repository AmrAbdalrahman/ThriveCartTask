<?php

namespace App\Strategies\Checkout;

class FreeDeliveryStrategy implements DeliveryCostStrategyInterface
{
    /**
     * @param float $amount
     * @return float
     */
    public function calculateCost(float $amount): float
    {
        return 0;
    }
}

