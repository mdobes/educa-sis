<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount' => 'required|int|min:1|max_digits::11',
            'payment_id' => ['required', 'max:255', Rule::exists("payments_list", "id")],
            "type" => "string|in:bank_transfer,cash",
            "note" => "max:255"
        ];
    }
}
