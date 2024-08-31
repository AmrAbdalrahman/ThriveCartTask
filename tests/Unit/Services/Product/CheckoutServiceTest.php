<?php

namespace Tests\Unit\Services\Product;

use App\Contracts\Requests\CheckoutRequestInterface;
use App\DTOs\ProductDTO;
use App\Enums\DeliveryFeesEnum;
use App\Enums\ProductCodeEnum;
use App\Repositories\Product\ProductRepository;
use App\Services\Product\CheckoutService;
use Mockery;
use PHPUnit\Framework\TestCase;

class CheckoutServiceTest extends TestCase
{
    /**
     * @var CheckoutService
     */
    protected CheckoutService $service;

    protected ProductRepository|Mockery\MockInterface|Mockery\LegacyMockInterface $repositoryMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->repositoryMock = Mockery::mock(ProductRepository::class);
        $this->service = new CheckoutService($this->repositoryMock);
    }

    /**
     * @return void
     */
    public function testCalculateCheckoutWithSingleProduct(): void
    {
        $productDTO = new ProductDTO(ProductCodeEnum::BLUE_WIDGET->value, 5);
        $request = Mockery::mock(CheckoutRequestInterface::class);
        $request->shouldReceive('getProducts')->andReturn([$productDTO]);

        $this->repositoryMock->shouldReceive('findAllByWhereIn')
            ->once()
            ->with('code', ['B01'], ['code', 'price'])
            ->andReturn(collect([
                ['code' => 'B01', 'price' => 7.95],
            ]));


        $totalAmount = 7.95 * 5;
        $expectedDeliveryCost = (float)DeliveryFeesEnum::UNDER_FIFTY->value;
        $total = $this->service->calculateCheckout($request);

        $expectedTotal = $totalAmount + $expectedDeliveryCost;
        $this->assertEquals($expectedTotal, $total);
    }

    /**
     * @return void
     */
    public function testCalculateCheckoutWithRedWidgetOffer(): void
    {
        $productDTO = new ProductDTO(ProductCodeEnum::RED_WIDGET->value, 3);
        $request = Mockery::mock(CheckoutRequestInterface::class);
        $request->shouldReceive('getProducts')->andReturn([$productDTO]);

        $this->repositoryMock->shouldReceive('findAllByWhereIn')
            ->once()
            ->with('code', [ProductCodeEnum::RED_WIDGET->value], ['code', 'price'])
            ->andReturn(collect([
                ['code' => ProductCodeEnum::RED_WIDGET->value, 'price' => 10.00],
            ]));

        // Calculate total amount with offer: 10.00 * 2 (full price) + 5.00 (discounted) = 25.00
        $totalAmount = 10.00 * 2 + 5.00;
        $expectedDeliveryCost = (float)DeliveryFeesEnum::UNDER_FIFTY->value;
        $total = $this->service->calculateCheckout($request);

        $expectedTotal = $totalAmount + $expectedDeliveryCost;
        $this->assertEquals($expectedTotal, $total);
    }

    /**
     * @return void
     */
    public function testCalculateCheckoutWithDeliveryCost(): void
    {
        $productDTO = new ProductDTO(ProductCodeEnum::GREEN_WIDGET->value, 2);
        $request = Mockery::mock(CheckoutRequestInterface::class);
        $request->shouldReceive('getProducts')->andReturn([$productDTO]);

        $this->repositoryMock->shouldReceive('findAllByWhereIn')
            ->once()
            ->with('code', ['G01'], ['code', 'price'])
            ->andReturn(collect([
                ['code' => 'G01', 'price' => 24.95],
            ]));

        $totalAmount = 24.95 * 2;
        $expectedDeliveryCost = (float)DeliveryFeesEnum::UNDER_FIFTY->value;
        $total = $this->service->calculateCheckout($request);

        $expectedTotal = $totalAmount + $expectedDeliveryCost;
        $this->assertEquals($expectedTotal, $total);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
