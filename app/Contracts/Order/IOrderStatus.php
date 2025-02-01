<?php

namespace App\Contracts\Order;
use App\Models\Order;

/**
 * Interface IOrderStatus used to handle Order Status change
 */
interface IOrderStatus{
    /**
     * Do the update of order status based on the actual implementation
     * @param int $orderId from the payment gateway
     * @param array $data
     * @return Order
     */
    public function update($orderId, $data): Order;
}