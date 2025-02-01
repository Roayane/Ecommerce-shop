<?php

namespace App\Implementations\Order;
use App\Adapters\OrderStatusAdapter;
use App\Contracts\Order\IOrderStatus;
use App\Enums\OrderStatus;
use App\Mail\Orders\PaidOrderMail;
use App\Models\Order;
use Mail;

class PaidOrder implements IOrderStatus
{
    public function __construct(public OrderStatusAdapter $orderStatusAdapter)
    {
    }
    public function update($orderId, $data): Order
    {
        $order = Order::query()
            ->where('gateway_order_id', $orderId)
            ->first();
        if($order->status == OrderStatus::PAID) {
            return $order;
        }
        $order->status = OrderStatus::PAID;
        $order->details = json_encode($data);
        $order->save();

        $orderDto = $this->orderStatusAdapter
            ->getOrderUpdated($data);

        Mail::to($orderDto->customerEmail)
            ->queue(new PaidOrderMail($orderDto));

        return $order;
    }
}