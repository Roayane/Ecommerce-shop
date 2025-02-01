<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/confirm', function (Request $request) {
    $data = $request->all();
    $orderId = $data['klarna_order_id'];
    return redirect(config('payment.thank_you_url') . '?order_id=' . $orderId);
});