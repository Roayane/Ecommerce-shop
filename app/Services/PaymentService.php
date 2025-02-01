<?php

namespace App\Services;
use App\Contracts\Payment\IPaymentGateway;
use App\Contracts\Payment\IPaymentInterceptor;
use App\Strategies\PaymentGatewayStrategy;

class PaymentService
{

    public function __construct(
        public IPaymentGateway $paymentGateway,
        public ProductService $productService,
        public IPaymentInterceptor $paymentInterceptor,
        public PaymentGatewayStrategy $paymentGatewayStrategy
    ) {
    }

    public function createOrder($productId, $quantity)
    {
        $product = $this->productService->getById($productId);
        $order = $this->paymentGateway->placeOrder($product, $quantity);

        return $this->paymentInterceptor->handle($order);
    }

    public function handleWebhook($payload)
    {
        $gateway = $this->paymentGatewayStrategy->getPaymentGateway();
        $gateway->handleWebhook($payload);
    }
}