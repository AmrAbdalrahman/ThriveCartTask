<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductDTO;
use PHPUnit\Framework\TestCase;

class ProductDTOTest extends TestCase
{
    public function testConstructor(): void
    {
        $dto = new ProductDTO('code1', 10);
        $this->assertEquals('code1', $dto->code);
        $this->assertEquals(10, $dto->quantity);
    }

    public function testFromArray(): void
    {
        $data = ['code' => 'code1', 'quantity' => 10];
        $dto = ProductDTO::fromArray($data);
        $this->assertInstanceOf(ProductDTO::class, $dto);
        $this->assertEquals('code1', $dto->code);
        $this->assertEquals(10, $dto->quantity);
    }
}
