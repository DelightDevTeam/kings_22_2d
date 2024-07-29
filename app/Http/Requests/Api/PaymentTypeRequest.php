<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PaymentTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_type_id' => 'required',
            'account_name' => ['required', 'string'],
            'account_no' => ['required', 'numeric'],
            'password' => 'required',
        ];
    }
}
