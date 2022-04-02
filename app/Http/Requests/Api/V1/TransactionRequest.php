<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array
     */
    public function rules()
    {
        return [
            'amount'                    => 'required|numeric',
            'due_on'                    => 'required|date|date_format:Y-m-d',
            'vat'                       => 'required|numeric',
            'vat_included'              => 'required|boolean',
            'payer'                     => 'required|exists:users,id',
            'category_id'               => 'required|exists:categories,id',
            'subcategory_id'            => 'nullable|exists:sub_categories,id',
            'payment'                   => 'nullable|array',
            'payment.*.amount'          => 'required|numeric',
            'payment.*.paid_on'         => 'required|date|date_format:Y-m-d',
            'payment.*.payment_method'  => 'required|string',
            'payment.*.details'         => 'nullable|string'
        ];
    }
}
