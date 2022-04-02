<?php


namespace App\Support\Api\V1;


use App\Models\Transaction;

class PaymentRecordSupport
{
    public $amount;
    public $paid_on;

    public function __construct($amount, $paid_on, $details)
    {
        $this->amount           = $amount;
        $this->paid_on          = $paid_on;
        $this->details          = $details;
    }

    public static function fromRequest($request)
    {
        return new static(
            $request->get('amount'),
            $request->get('due_on'),
            $request->get('vat')
        );
    }

    public function get($key)
    {
        if (!isset($this->$key))
            throw new \Exception('Trying to get invalid variable!');

        return $this->$key;
    }

}
