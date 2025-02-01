<?php

namespace Tests\Feature;

use App\Dtos\ProductDto;
use App\Enums\PaymentGateways;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_that_it_returns_422_response_on_invalid_request(): void
    {
        //When
        $response = $this->postJson('/api/v1/payments/checkout', [], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        //Assert
        $response->assertJsonValidationErrors([
            'payment_gateway',
            'quantity',
            'product_id'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_that_it_create_the_checkout_request(): void
    {
        //When
        $product = ProductDto::fake(
            "https://img.freepik.com/premium-photo/cosmetic-products-presentation-mockup-showcase_1277677-4277.jpg?w=740"
        );
        $savedProduct = Product::create($product->toArray());

        //Then
        $response = $this->postJson('/api/v1/payments/checkout', [
            'payment_gateway' => PaymentGateways::KLARNA->value,
            'quantity' => 1,
            'product_id' => $savedProduct->id
        ], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        $ordersCount = Order::query()->count();

        //Assert
        $response->assertJsonStructure([
            'data' => [
                'order_id',
                'html_snippet',
            ],
            'message',
            'success',
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertEquals(1, $ordersCount);
    }

    public function test_that_webhook_reject_request_with_invalid_signature(): void
    {
        //When
        $response = $this->postJson('/api/v1/payments/handle-webhook', [], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        $ordersCount = Order::query()->count();

        //Assert
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $this->assertEquals(0, $ordersCount);
    }

    public function test_that_webhook_accept_request_with_valid_signature(): void
    {
        //When
        //Creating product
        $product = ProductDto::fake(
            "https://img.freepik.com/premium-photo/cosmetic-products-presentation-mockup-showcase_1277677-4277.jpg?w=740"
        );
        $savedProduct = Product::create($product->toArray());

        //Creating the checkout request
        $response = $this->postJson('/api/v1/payments/checkout', [
            'payment_gateway' => PaymentGateways::KLARNA->value,
            'quantity' => 1,
            'product_id' => $savedProduct->id
        ], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        //Fetching the created order
        $order = Order::query()->first();
        $orderGatewayId = $order->gateway_order_id;
        $secretToken = config('payment.gateways.klarna.secret_key');

        //Then
        $webhookResponse = $this->postJson("/api/v1/payments/handle-webhook?secretToken={$secretToken}&klarna_order_id={$orderGatewayId}", [], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
        
        //Assert
        $webhookResponse->assertStatus(Response::HTTP_OK);
        $this->assertEquals(1, Order::query()->count());
    }
}
