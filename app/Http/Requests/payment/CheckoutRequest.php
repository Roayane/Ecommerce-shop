<?php

namespace App\Http\Requests\payment;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_gateway' => 'required|in:klarna',
            'quantity' => 'required|min:1',
            'product_id' => 'required|exists:products,id',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_gateway.required' => 'Gateway is required',
            'payment_gateway.in' => 'Invalid gateway',
            'quantity.min' => 'Quantity is too short',
            'product_id.exists' => 'Product not found',
        ];
    }
}
