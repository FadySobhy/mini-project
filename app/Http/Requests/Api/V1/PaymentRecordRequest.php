<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRecordRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'transaction_id'    => 'required|exists:transactions,id',
            'amount'            => 'required|numeric',
            'paid_on'           => 'required|date|date_format:Y-m-d',
            'payment_method'    => 'required|string',
            'details'           => 'nullable|string'
        ];
    }
}
