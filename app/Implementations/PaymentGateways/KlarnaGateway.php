<?php

namespace App\Implementations\PaymentGateways;
use App\Contracts\Payment\IPaymentGateway;
use App\Exceptions\EmptyPaymentCredentialsConfiguration;
use App\Exceptions\WebhookValidationException;
use App\Models\Product;
use App\Strategies\OrderStrategy;
use Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use PHPUnit\Framework\UnknownTypeException;
use SebastianBergmann\Diff\ConfigurationException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class KlarnaGateway implements IPaymentGateway
{
    private string $api;
    private string $username;
    private string $password;
    private string $klarnaSecretKey;

    public function __construct(public OrderStrategy $orderStrategy)
    {
        $this->api = config('payment.gateways.klarna.api_url');
        $this->username = config('payment.gateways.klarna.username');
        $this->password = config('payment.gateways.klarna.password');
        $this->klarnaSecretKey = config('payment.gateways.klarna.secret_key');
        if ($this->api == null || $this->username == null || $this->password == null) {
            throw new EmptyPaymentCredentialsConfiguration('Klarna credentials not configured');
        }
    }

    public function placeOrder(Product $product, int $quantity): mixed
    {
        info("Placing order for product " . $product->name . " with quantity " . $quantity . " using Klarna gateway");
        $amount = $quantity * $product->price;

        $purchase_country = "US";
        $purchase_currency = "USD";
        $local = "en-US";
        $order_amount = $amount;
        $order_tax_amount = 0;

        //Asserting we have only one product
        $order_lines = [
            [
                'type' => 'physical',
                'name' => $product->name,
                'image_url' => $product->image_url,
                'quantity' => $quantity,
                'unit_price' => $product->price,
                'tax_rate' => 0,
                'total_amount' => $order_amount,
                'total_tax_amount' => $order_tax_amount,
            ]
        ];

        $confirmationURL = config('payment.gateways.klarna.redirecting.confirmation');
        $pushURL = config('payment.gateways.klarna.redirecting.push');

        $data = [
            'purchase_country' => $purchase_country,
            'purchase_currency' => $purchase_currency,
            'local' => $local,
            'order_amount' => $order_amount,
            'order_tax_amount' => $order_tax_amount,
            'order_lines' => $order_lines,
            'merchant_urls' => [
                'terms' => config('payment.gateways.klarna.redirecting.terms'),
                'checkout' => config('payment.gateways.klarna.redirecting.checkout'),
                'confirmation' => "{$confirmationURL}?klarna_order_id={checkout.order.id}",
                'push' => "{$pushURL}?klarna_order_id={checkout.order.id}&secretToken={$this->klarnaSecretKey}",
            ]
        ];

        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Klarna-Partner', 'string')
            ->withBody(json_encode($data))
            ->post("{$this->api}/checkout/v3/orders");

        return $response->json();
    }

    public function handleWebhook(array $data): void
    {
        info("Klarna webhook triggered", [$data]);
        $secretToken = $data['secretToken'];

        if ($secretToken != $this->klarnaSecretKey) {
            Log::error("Invalid secret token");
            throw new WebhookValidationException("Invalid secret token");
        }

        $orderId = $data['klarna_order_id'];

        if ($orderId == null || $orderId == "") {
            Log::error("Klarna order id not found");
            throw new WebhookValidationException("Klarna order id not found");
        }

        $response = Http::withBasicAuth($this->username, $this->password)
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Klarna-Partner', 'string')
            ->withBody(json_encode($data))
            ->get("{$this->api}/checkout/v3/orders/{$orderId}");

        if($response->getStatusCode() == SymfonyResponse::HTTP_NOT_FOUND){
            Log::error("Order {$orderId} not found");
            throw new ResourceNotFoundException("Order {$orderId} not found");

        } else if ($response->getStatusCode() != SymfonyResponse::HTTP_OK) {
            Log::error("Klarna error", [
                'code' => $response->getStatusCode(),
                'body' => $response->body(),
            ]);
            throw new UnknownTypeException("Klarna error");

        }
        $order = $response->json();
        info("Requested order {$orderId} status", [$order]);
        $orderStatusHandler = $this->orderStrategy
                                    ->determineOrderHandler($order['status']);

        $orderStatusHandler->update($orderId, $order);
    }
}