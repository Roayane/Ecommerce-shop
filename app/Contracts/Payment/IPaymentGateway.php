<?php

namespace App\Contracts\Payment;
use App\Models\Product;

/**
 * Interface IPaymentGateway used to handle All Payment Gateway Operations 
 */
interface IPaymentGateway
{
    /**
     * Place an order based on the actual implementation
     * @param Product $product
     * @param int $quantity
     * @return mixed
     * */
    public function placeOrder(Product $product, int $quantity): mixed;

    /**
     * Handle webhook callback 
     * @param array $data
     * @return void
     * */
    public function handleWebhook(array $data): void;
}
