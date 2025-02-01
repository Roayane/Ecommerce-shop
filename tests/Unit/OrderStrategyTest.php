<?php

namespace Tests\Unit;

use App\Adapters\OrderStatusAdapter;
use App\Implementations\Order\FailedOrder;
use App\Implementations\Order\PaidOrder;
use App\Strategies\OrderStrategy;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class OrderStrategyTest extends TestCase
{
    private OrderStrategy $orderStrategy;
    public function setUp(): void
    {
        parent::setUp();
        $this->orderStrategy = App::make(OrderStrategy::class);
    }
    /**
     * A basic unit test example.
     */
    public function test_that_it_creates_right_strategy(): void
    {
        //when
        $paidOrderInstance = $this->orderStrategy
                                ->determineOrderHandler('checkout_complete');
        $failedOrderInstance = $this->orderStrategy
                                ->determineOrderHandler('checkout_failed');
        //Assert
        $this->assertInstanceOf(PaidOrder::class, $paidOrderInstance);
        $this->assertInstanceOf(FailedOrder::class, $failedOrderInstance);
    }
}
