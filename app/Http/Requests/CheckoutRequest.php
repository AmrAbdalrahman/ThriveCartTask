<?php

namespace App\Http\Requests;

use App\Contracts\Requests\CheckoutRequestInterface;
use App\DTOs\ProductDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest implements CheckoutRequestInterface
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'products' => 'required|array|min:1',
            'products.*.code' => 'required|string|exists:products,code',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'products.required' => 'At least one product must be added to the basket.',
            'products.*.code.required' => 'Each product must have a valid code.',
            'products.*.code.exists' => 'The selected product code is invalid.',
            'products.*.quantity.required' => 'Each product must have a quantity.',
            'products.*.quantity.min' => 'The quantity must be at least 1.',
        ];
    }

    /**
     * Get the products from the request.
     *
     * @return ProductDTO[]
     */
    public function getProducts(): array
    {
        return array_map(fn($product) => ProductDTO::fromArray($product), $this->input('products'));
    }
}
