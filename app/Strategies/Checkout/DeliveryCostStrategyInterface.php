<?php

namespace App\Strategies\Checkout;

interface DeliveryCostStrategyInterface
{
    /**
     * @param float $amount
     * @return float
     */
    public function calculateCost(float $amount): float;
}
