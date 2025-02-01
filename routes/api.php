<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ProductController;


Route::resource('products', ProductController::class);
Route::prefix('payments')->group(function () {
    Route::post('/checkout', [PaymentController::class, 'checkout']);
    Route::post('/handle-webhook', [PaymentController::class, 'handleWebhook']);
});
Route::get('orders/{orderId}', [OrderController::class, 'getOrder']);