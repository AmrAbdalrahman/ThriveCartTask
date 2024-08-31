<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\HttpStatusCodeEnum;
use App\Http\Controllers\API\ApiController;
use App\Http\Requests\CheckoutRequest;
use App\Services\Product\CheckoutService;
use Illuminate\Http\JsonResponse;
use Throwable;

class CheckoutController extends ApiController
{
    /**
     * @var CheckoutService
     */
    protected CheckoutService $service;

    /**
     * @param CheckoutService $productService
     */
    public function __construct(
        CheckoutService $productService,
    )
    {
        $this->service = $productService;
    }


    /**
     * @param CheckoutRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function checkout(CheckoutRequest $request): JsonResponse
    {
        $total = $this->service->calculateCheckout($request);
        return $this->response(['total' => $total], HttpStatusCodeEnum::OK->value, 'Checkout processed successfully');
    }
}
