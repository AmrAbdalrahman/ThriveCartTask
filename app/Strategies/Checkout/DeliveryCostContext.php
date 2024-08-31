<?php

namespace App\Strategies\Checkout;

class DeliveryCostContext
{
    private DeliveryCostStrategyInterface $strategy;

    /**
     * @param DeliveryCostStrategyInterface $strategy
     */
    public function __construct(DeliveryCostStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    /**
     * @param float $amount
     * @return float
     */
    public function getCost(float $amount): float
    {
        return $this->strategy->calculateCost($amount);
    }
}

