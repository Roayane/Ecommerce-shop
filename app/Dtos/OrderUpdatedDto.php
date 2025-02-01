<?php

namespace App\Dtos;

class OrderUpdatedDto
{

    public function __construct(
        public $orderId,
        public $productName,
        public $price,
        public $quantity,
        public $customerName,
        public $customerEmail
    ) {
    }
}