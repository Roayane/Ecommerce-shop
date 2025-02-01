<?php

namespace App\Dtos;

class CheckoutRequestDto
{

    public function __construct(
        public int $orderAmount,
        public int $orderTaxAmount = 0,
        public int $taxRate,
        public int $quantity,
        public int $productId
    ) {
    }

    public static function make(array $data): CheckoutRequestDto{
        return new self(
            $data['order_amount'],
            $data['order_tax_amount'],
            $data['tax_rate'],
            $data['quantity'],
            $data['product_id']
        );
    }
}