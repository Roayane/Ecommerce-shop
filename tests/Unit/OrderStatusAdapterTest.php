<?php

namespace Tests\Unit;

use App\Adapters\OrderStatusAdapter;
use App\Enums\OrderStatus;
use App\Enums\PaymentGateways;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class OrderStatusAdapterTest extends TestCase
{
    private OrderStatusAdapter $orderStatusAdapter;
    public function setUp(): void{
        parent::setUp();
        $this->orderStatusAdapter = App::make(OrderStatusAdapter::class);
    }
    /**
     * A basic unit test example.
     */
    public function test_that_get_status_returns_our_system_status(): void
    {

        //Then
        $statusPending = $this->orderStatusAdapter->getStatus('CHECKOUT_INCOMPLETE');
        $statusPaid = $this->orderStatusAdapter->getStatus('checkout_complete');
        $statusCancelled = $this->orderStatusAdapter->getStatus('checkout_cancelled');
        $statusFailed = $this->orderStatusAdapter->getStatus('checkout_failed');
        //Assets
        $this->assertEquals(OrderStatus::PAID, $statusPaid);
        $this->assertEquals(OrderStatus::PENDING, $statusPending);
        $this->assertEquals(OrderStatus::CANCELLED, $statusCancelled);
        $this->assertEquals(OrderStatus::FAILED, $statusFailed);
    }

    public function test_that_it_throws_exception_for_invalid_status(): void{
        
        //Assert
        $this->expectException(Exception::class);
        
        //Then
        $this->orderStatusAdapter->getStatus('UNKNOWN');
    }
}
