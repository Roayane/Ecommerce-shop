<?php

namespace App\Implementations\Order;
use App\Contracts\Order\IOrderStatus;
use App\Enums\OrderStatus;
use App\Models\Order;

class CancelledOrder implements IOrderStatus
{
    public function update($orderId, $data): Order
    {
        $order = Order::query()
            ->where('gateway_order_id', $orderId)
            ->first();
        $order->status = OrderStatus::CANCELLED;
        $order->details = json_encode($data);
        $order->save();

        //NOTICE: we may send mail to merchant
        
        return $order;
    }
}