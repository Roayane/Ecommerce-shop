<?php

namespace App\Http\Controllers;

use App\Dtos\CheckoutRequestDto;
use App\Exceptions\WebhookValidationException;
use App\Http\Requests\payment\CheckoutRequest;
use App\Services\PaymentService;
use App\Services\ProductService;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class PaymentController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private PaymentService $paymentService
    ) {
    }
    public function checkout(CheckoutRequest $request)
    {
        try {
            $data = $request->validated();

            $order = $this->paymentService->createOrder(
                $data['product_id'],
                $data['quantity']
            );

            return $this->json($order, Response::HTTP_CREATED);

        } catch (Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->json(
                null,
                Response::HTTP_BAD_REQUEST,
                $e->getMessage()
            );
        }
    }

    public function handleWebhook(Request $request)
    {
        info("Webhook received");
        try {

            $payload = $request->all();

            $this->paymentService->handleWebhook($payload);

            return response()->json(null, Response::HTTP_OK);

        } catch (WebhookValidationException $e) {
            return $this->json(
                null,
                Response::HTTP_BAD_REQUEST,
                $e->getMessage()
            );

        } catch (ResourceNotFoundException $e) {
            return $this->json(
                null,
                Response::HTTP_NOT_FOUND,
                "Order not found"
            );

        } catch (Exception $e) {
            Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return $this->json(
                null,
                Response::HTTP_BAD_REQUEST,
                $e->getMessage()
            );
        }
    }
}
