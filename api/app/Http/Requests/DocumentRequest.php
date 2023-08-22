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

    public function attributes(): array
    {
        return [
            'provider' => 'Наименование поставщика',
            'provider_inn' => 'ИНН поставщика',
            'provider_kpp' => 'КПП поставщика',
            'company_logo' => 'Лого компании',
            'customer_full_name' => 'ФИО покупателя',
            'customer_inn' => 'ИНН покупателя',
            'products.*.name' => 'Наименование товара',
            'products.*.count' => 'Количество',
            'products.*.price' => 'Сумма',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Поле ":attribute" обязательно для заполнения.',
            'min' => [
                'numeric' => 'Поле ":attribute" должно быть не менее :min.',
                'file' => 'Поле ":attribute" должно быть не менее :min Килобайт.',
                'string' => 'Поле ":attribute" должно быть не короче :min символов.',
                'array' => 'Поле ":attribute" должно содержать не менее :min элементов.'
            ],
            'max' => [
                'numeric' => 'Поле ":attribute" должно быть не больше :max.',
                'file' => 'Поле ":attribute" должно быть не больше :max Килобайт.',
                'string' => 'Поле ":attribute" должно быть не длиннее :max символов.',
                'array' => 'Поле ":attribute" должно содержать не более :max элементов.'
            ],
            'integer' => 'Поле ":attribute" должно быть целым числом.',
            'image' => 'Поле ":attribute" должно быть изображением.',
            'array' => 'Поле ":attribute" должно быть массивом.',
            'numeric' => 'Поле ":attribute" должно быть числом.',
            'products.required' => 'Необходимо добавить товары',
        ];
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
            'company_logo' => ['required', 'image', 'max:4096'],
            'customer_full_name' => ['required', 'string', 'min:3', 'max:255'],
            'customer_inn' => ['required', 'integer', 'min_digits:3', 'max_digits:30'],
            'products' => ['required', 'array', 'min:1',],
            'products.*' => ['required', 'array'],
            'products.*.name' => ['required', 'string', 'min:3', 'max:255'],
            'products.*.count' => ['required', 'integer'],
            'products.*.price' => ['required', 'numeric', 'min:3', 'max:255'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
