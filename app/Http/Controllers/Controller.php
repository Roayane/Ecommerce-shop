<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    protected function json($data, $code = Response::HTTP_OK, $message = null){
        return response()->json([
            'data' => $data,
            'message' => $message,
            'success' => $code < 400
        ], $code);
    }
}
