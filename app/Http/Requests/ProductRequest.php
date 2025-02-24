<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use ProtoneMedia\LaravelMixins\Request\ConvertsBase64ToFiles;

class ProductRequest extends FormRequest
{
    use ConvertsBase64ToFiles;
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
            'seller_id' => 'required|exists:sellers,id',
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'photo' => 'nullable|file|image',
        ];
    }

    private function updateRules(): array
    {
        return [
            'seller_id' => 'required|exists:sellers,id',
            'name' => 'required|string|max:100',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'photo' => 'nullable|file|image',
        ];
    }

    protected function base64FileKeys(): array
    {
        return [
            'photo' => 'foto-product.jpg',
        ];
    }
}
