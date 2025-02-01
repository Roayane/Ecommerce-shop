<?php

namespace App\Strategies;
use App\Adapters\OrderStatusAdapter;
use App\Contracts\Order\IOrderStatus;
use App\Enums\OrderStatus;
use App\Implementations\Order\CancelledOrder;
use App\Implementations\Order\FailedOrder;
use App\Implementations\Order\PaidOrder;
use App\Implementations\Order\PendingOrder;
use Illuminate\Support\Facades\App;

class OrderStrategy
{
    public function __construct(public OrderStatusAdapter $orderStatusAdapter)
    {
    }
    public function determineOrderHandler($orderStatus): IOrderStatus
    {
        $status = $this->orderStatusAdapter->getStatus($orderStatus);

        return match ($status) {
            OrderStatus::PENDING => App::make(PendingOrder::class),
            OrderStatus::PAID => App::make(PaidOrder::class),
            OrderStatus::CANCELLED => App::make(CancelledOrder::class),
            OrderStatus::FAILED => App::make(FailedOrder::class),
            default => throw new \Exception('Invalid order status'),
        };

    }
}