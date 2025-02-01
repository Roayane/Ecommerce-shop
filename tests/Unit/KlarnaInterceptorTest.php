<?php

namespace Tests\Unit;

use App\Implementations\Interceptors\KlarnaInterceptor;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class KlarnaInterceptorTest extends TestCase
{
    private KlarnaInterceptor $klarnaInterceptor;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->klarnaInterceptor = App::make(KlarnaInterceptor::class);
    }

    public function test_that_klarna_interceptor_returns_correct_response(): void{
        //When
        $payload = [
            'order_id' => '123',
            'order_amount' => 100,
            'html_snippet' => '<p>Hello</p>',
        ];
        $response = $this->klarnaInterceptor->handle($payload);
        
        //Then
        $order = Order::query()->first();

        //Assert
        $this->assertNotNull($order);
        $this->assertEquals($order->gateway_order_id, $payload['order_id']);
        $this->assertEquals($response['html_snippet'], $payload['html_snippet']);
        $this->assertEquals($response['order_id'], $payload['order_id']);
    }

    public function test_that_it_throws_exception(): void
    {
        $this->expectExceptionMessage('Invalid Klarna response');
        $this->klarnaInterceptor->handle(['invalid']);
    }
}
