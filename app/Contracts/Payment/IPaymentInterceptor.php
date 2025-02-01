<?php

namespace App\Contracts\Payment;


/**
 * Interface IPaymentInterceptor used to intercept payment gateway response 
 * when order's created
 */
interface IPaymentInterceptor
{
    /**
     * Intercept the new order do what the implementation expects
     * and return the data that the client expects
     * @param mixed $data
     * @return mixed
     */
    public function handle(mixed $data): mixed;
}