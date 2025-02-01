<?php

namespace App\Implementations\Interceptors;

use App\Contracts\Payment\IPaymentInterceptor;
use App\Enums\OrderStatus;
use App\Models\Order;

class KlarnaInterceptor implements IPaymentInterceptor
{

    public function handle(mixed $data): mixed
    {
        if(isset($data['html_snippet']) && isset($data['order_id'])) {
            Order::create([
                'gateway_order_id' => $data['order_id'],
                'status' => OrderStatus::PENDING,
                'total' => $data['order_amount'],
                'details' => json_encode($data),
            ]);
            return [
                'order_id' => $data['order_id'],
                'html_snippet' => $data['html_snippet'],
            ];
        }
        throw new \Exception('Invalid Klarna response');
    }
}