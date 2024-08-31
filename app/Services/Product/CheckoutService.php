<?php

namespace App\Services\Product;

use App\Contracts\Requests\CheckoutRequestInterface;
use App\DTOs\ProductDTO;
use App\Enums\CheckoutAmountEnum;
use App\Enums\ProductCodeEnum;
use App\Repositories\BaseRepository;
use App\Repositories\Product\ProductRepository;
use App\Services\BaseService;
use App\Strategies\Checkout\FreeDeliveryStrategy;
use App\Strategies\Checkout\UnderFiftyStrategy;
use App\Strategies\Checkout\UnderNinetyStrategy;

class CheckoutService extends BaseService
{
    /**
     * @var ProductRepository
     */
    protected BaseRepository $repository;

    /**
     * @var string
     */
    protected string $entity = 'Product';

    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        parent::__construct($productRepository);
    }

    /**
     * Calculate the checkout total including delivery cost and special offers.
     *
     * @param CheckoutRequestInterface $request
     * @return float
     */
    public function calculateCheckout(CheckoutRequestInterface $request): float
    {
        $productDTOs = $request->getProducts();
        $mergedProducts = $this->mergeProducts($productDTOs);
        $productCodes = $this->extractProductCodes($mergedProducts);

        $productDetails = $this->fetchProductDetails($productCodes);
        $totalAmount = $this->calculateTotalAmount($mergedProducts, $productDetails);
        $deliveryCost = $this->calculateDeliveryCost($totalAmount);

        $total = $totalAmount + $deliveryCost;
        return $this->truncateToDecimal($total);
    }

    /**
     * Truncate a number to a specific number of decimal places without rounding.
     *
     * @param float $number
     * @return float
     */
    private function truncateToDecimal(float $number): float
    {
        $factor = pow(10, 2);
        return (float)number_format(floor($number * $factor) / $factor, 2, '.', '');
    }

    /**
     * Merge products with the same code and update their quantity.
     *
     * @param array $productDTOs
     * @return array
     */
    private function mergeProducts(array $productDTOs): array
    {
        $merged = [];

        foreach ($productDTOs as $productDTO) {
            $code = $productDTO->code;
            if (isset($merged[$code])) {
                $merged[$code]->quantity += $productDTO->quantity;
            } else {
                $merged[$code] = new ProductDTO($productDTO->code, $productDTO->quantity);
            }
        }

        return array_values($merged);
    }

    /**
     * Extract product codes from the list of ProductDTOs.
     *
     * @param array $productDTOs
     * @return array
     */
    private function extractProductCodes(array $productDTOs): array
    {
        return array_map(fn(ProductDTO $productDTO) => $productDTO->code, $productDTOs);
    }

    /**
     * Fetch product details from the repository.
     *
     * @param array $productCodes
     * @return array
     */
    private function fetchProductDetails(array $productCodes): array
    {
        return $this->repository->findAllByWhereIn('code', $productCodes, ['code', 'price'])->toArray();
    }

    /**
     * Calculate the total amount, applying offers as necessary.
     *
     * @param array $productDTOs
     * @param array $productDetails
     * @return float
     */
    private function calculateTotalAmount(array $productDTOs, array $productDetails): float
    {
        $productPrices = array_column($productDetails, 'price', 'code');
        $totalAmount = 0.0;

        foreach ($productDTOs as $productDTO) {
            $code = $productDTO->code;
            $quantity = $productDTO->quantity;
            $price = $productPrices[$code] ?? 0;

            if ($code === ProductCodeEnum::RED_WIDGET->value) {
                $totalAmount += $this->applyRedWidgetOffer($quantity, $price);
            } else {
                $totalAmount += $quantity * $price;
            }
        }

        return $totalAmount;
    }

    /**
     * Apply the special offer for Red Widgets.
     *
     * @param int $quantity
     * @param float $price
     * @return float
     */
    private function applyRedWidgetOffer(int $quantity, float $price): float
    {
        $fullPriceQuantity = intdiv($quantity, 2) + ($quantity % 2);
        $discountedQuantity = intdiv($quantity, 2);

        $fullPriceTotal = $fullPriceQuantity * $price;
        $discountedTotal = $discountedQuantity * ($price / 2);

        return $fullPriceTotal + $discountedTotal;
    }

    /**
     * Calculate the delivery cost based on the total amount.
     *
     * @param float $totalAmount
     * @return float
     */
    private function calculateDeliveryCost(float $totalAmount): float
    {
        $strategy = $this->getDeliveryCostStrategy($totalAmount);
        return $strategy->calculateCost($totalAmount);
    }

    /**
     * Determine the appropriate delivery cost strategy based on the total amount.
     *
     * @param float $amount
     * @return FreeDeliveryStrategy|UnderNinetyStrategy|UnderFiftyStrategy
     */
    private function getDeliveryCostStrategy(float $amount): FreeDeliveryStrategy|UnderNinetyStrategy|UnderFiftyStrategy
    {
        if ($amount >= CheckoutAmountEnum::NINETY->value) {
            return new FreeDeliveryStrategy();
        } elseif ($amount >= CheckoutAmountEnum::FIFTY->value) {
            return new UnderNinetyStrategy();
        } else {
            return new UnderFiftyStrategy();
        }
    }
}
