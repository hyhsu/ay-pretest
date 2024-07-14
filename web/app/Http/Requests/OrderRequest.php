<?php

namespace App\Http\Requests;

use App\Rules\English;
use App\Rules\Capitalized;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class OrderRequest extends FormRequest
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
            'id'       => ['required'],
            'name'     => ['required', new English(), new Capitalized()],
            'address'  => ['required'],
            'price'    => ['required', 'numeric', 'max:' . config('order.max_price')],
            'currency' => ['required', 'in:' . config('order.currency')],
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'price.max'   => sprintf('Price is over %s', config('order.max_price')),
            'currency.in' => 'Currency format is wrong',
        ];
    }

    /**
     * @param Validator $validator
     *
     * @return mixed|void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors'  => $errors
        ], 400));
    }
}
