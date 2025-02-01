<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;


class OrderController extends Controller
{
    public function __construct()
    {
    }
    public function getOrder($orderId)
    {
        try {
            $order = Order::query()
                ->where('gateway_order_id', $orderId)
                ->first();
            if (!$order) {
                throw new ModelNotFoundException();
            }

            $orderDetails = json_decode($order->details);
            $orderData = [
                'id' => $order->id,
                'started_at' => $orderDetails->started_at,
                'product' => [
                    'name' => $orderDetails->order_lines[0]->name,
                    'quantity' => $orderDetails->order_lines[0]->quantity,
                    'image_url' => $orderDetails->order_lines[0]->image_url,
                ],
                'total' => $orderDetails->order_amount,
            ];

            return $this->json($orderData, Response::HTTP_OK);

        } catch (ModelNotFoundException $exception) {
            return $this->json(
                null,
                Response::HTTP_NOT_FOUND,
                'Order not found'
            );

        } catch (Exception $exception) {
            return $this->json(
                null,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $exception->getMessage()
            );
        }

    }
}
