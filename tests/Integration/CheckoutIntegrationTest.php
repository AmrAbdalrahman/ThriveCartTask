<?php

namespace Tests\Integration;

use App\Enums\HttpStatusCodeEnum;
use App\Enums\ProductCodeEnum;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckoutIntegrationTest extends TestCase
{
    use  WithFaker;

    /**
     * @return void
     */
    public function testValidationFailsWhenNoProductsProvided(): void
    {
        $response = $this->postJson('/api/v1/checkout', [
            'products' => []
        ]);

        $response->assertStatus(HttpStatusCodeEnum::UNPROCESSABLE_ENTITY->value)
            ->assertJson([
                'message' => 'At least one product must be added to the basket.',
                'errors' => [
                    'products' => [
                        'At least one product must be added to the basket.'
                    ]
                ]
            ]);
    }

    /**
     * @return void
     */
    public function testCheckoutWithB01AndG01(): void
    {
        $response = $this->postJson('/api/v1/checkout', [
            'products' => [
                ['code' => ProductCodeEnum::BLUE_WIDGET->value, 'quantity' => 1],
                ['code' => ProductCodeEnum::GREEN_WIDGET->value, 'quantity' => 1]
            ]
        ]);

        $response->assertStatus(HttpStatusCodeEnum::OK->value)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'total' => 37.85
                ],
                'code' => 200,
                'message' => 'Checkout processed successfully'
            ]);
    }

    /**
     * @return void
     */
    public function testCheckoutWithR01AndR01(): void
    {
        $response = $this->postJson('/api/v1/checkout', [
            'products' => [
                ['code' => ProductCodeEnum::RED_WIDGET->value, 'quantity' => 2]
            ]
        ]);

        $response->assertStatus(HttpStatusCodeEnum::OK->value)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'total' => 54.37
                ],
                'code' => 200,
                'message' => 'Checkout processed successfully'
            ]);
    }

    /**
     * @return void
     */
    public function testCheckoutWithR01AndG01(): void
    {
        $response = $this->postJson('/api/v1/checkout', [
            'products' => [
                ['code' => ProductCodeEnum::RED_WIDGET->value, 'quantity' => 1],
                ['code' => ProductCodeEnum::GREEN_WIDGET->value, 'quantity' => 1]
            ]
        ]);

        $response->assertStatus(HttpStatusCodeEnum::OK->value)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'total' => 60.85
                ],
                'code' => 200,
                'message' => 'Checkout processed successfully'
            ]);
    }

    /**
     * @return void
     */
    public function testCheckoutWithB01B01R01R01R01(): void
    {
        $response = $this->postJson('/api/v1/checkout', [
            'products' => [
                ['code' => ProductCodeEnum::BLUE_WIDGET->value, 'quantity' => 2],
                ['code' => ProductCodeEnum::RED_WIDGET->value, 'quantity' => 3]
            ]
        ]);

        $response->assertStatus(HttpStatusCodeEnum::OK->value)
            ->assertJson([
                'version' => 1,
                'data' => [
                    'total' => 98.27
                ],
                'code' => 200,
                'message' => 'Checkout processed successfully'
            ]);
    }
}
