<?php

namespace App\DTOs;

class ProductDTO
{
    public string $code;
    public int $quantity;

    /**
     * ProductDTO constructor.
     *
     * @param string $code
     * @param int $quantity
     */
    public function __construct(string $code, int $quantity)
    {
        $this->code = $code;
        $this->quantity = $quantity;
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            $data['code'] ?? '',
            $data['quantity'] ?? 0
        );
    }
}
