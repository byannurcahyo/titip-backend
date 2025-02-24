<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'address_id' => 'required|exists:address,id',
            'address' => 'required|string',
            'total_price' => 'required|numeric',
            'status' => 'nullable|in:waiting_payment,paid,delivered,canceled',
            'invoice_number' => 'required|string|max:50',
        ];
    }

    private function updateRules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'address_id' => 'required|exists:address,id',
            'address' => 'required|string',
            'total_price' => 'required|numeric',
            'status' => 'required|in:waiting_payment,paid,delivered,canceled',
            'invoice_number' => 'required|string|max:50',
        ];
    }
}
