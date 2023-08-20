<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DocumentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'provider' => ['required', 'string', 'min:3', 'max:255'],
            'provider_inn' => ['required', 'integer', 'min_digits:3', 'max_digits:30'],
            'provider_kpp' => ['required', 'integer', 'min_digits:3', 'max_digits:30'],
            'company_logo' => ['required', 'image'],
            'customer_full_name' => ['required', 'string', 'min:3', 'max:255'],
            'customer_inn' => ['required', 'string', 'min:3', 'max:255'],
            'products' => ['required', 'array', 'min:1',],
            'products.name' => ['required', 'string', 'min:3', 'max:255'],
            'products.count' => ['required', 'integer'],
            'products.price' => ['required', 'numeric', 'min:3', 'max:255'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
