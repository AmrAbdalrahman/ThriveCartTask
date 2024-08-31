<?php

namespace App\Contracts\Requests;

use App\DTOs\ProductDTO;

interface CheckoutRequestInterface
{
    /**
     * Get all input data.
     *
     * @param array|null $keys
     * @return array
     */
    public function all(array $keys = null);

    /**
     * Get the products from the request.
     *
     * @return ProductDTO[]
     */
    public function getProducts(): array;
}
