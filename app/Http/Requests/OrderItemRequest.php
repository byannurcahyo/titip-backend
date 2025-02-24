<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class OrderItemRequest extends FormRequest
{
    public $validator;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Tampilkan pesan error ketika validasi gagal
     *
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return $this->createRules();
        }
        return $this->updateRules();
    }

    private function createRules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'seller_id' => 'required|exists:sellers,id',
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'subTotal' => 'required|numeric',
        ];
    }

    private function updateRules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'seller_id' => 'required|exists:sellers,id',
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'subTotal' => 'required|numeric',
        ];
    }
}
