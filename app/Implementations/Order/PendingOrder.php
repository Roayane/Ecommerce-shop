<?php

namespace App\Implementations\Order;
use App\Adapters\OrderStatusAdapter;
use App\Contracts\Order\IOrderStatus;
use App\Enums\OrderStatus;
use App\Mail\Orders\PaidOrderMail;
use App\Models\Order;
use Mail;

class PendingOrder implements IOrderStatus
{
    public function __construct(public OrderStatusAdapter $orderStatusAdapter)
    {
    }
    public function update($orderId, $data): Order
    {
        $order = Order::query()
            ->where('gateway_order_id', $orderId)
            ->first();

        //Notice: we may notify the merchant in the future
        return $order;
    }
}