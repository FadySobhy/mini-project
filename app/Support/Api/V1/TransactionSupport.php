<?php


namespace App\Support\Api\V1;


use Carbon\Carbon;

class TransactionSupport
{
    public $amount;
    public $due_on;
    public $vat;
    public $vat_included;

    public function __construct($amount, $due_on, $vat, $vat_included)
    {
        $this->amount           = $amount;
        $this->due_on           = $due_on;
        $this->vat              = $vat;
        $this->vat_included     = $vat_included;
    }

    public static function fromRequest($request)
    {
        return new static(
            $request->get('amount'),
            $request->get('due_on'),
            $request->get('vat'),
            $request->get('vat_included')
        );
    }

    public function get($key)
    {
        if (!isset($this->$key))
            throw new \Exception('Trying to get invalid variable!');

        return $this->$key;
    }

    public function getTotalAmount()
    {
        $amount = $this->amount;
        if (!$this->vat_included)
            $amount = $amount + ($amount * $this->vat / 100);

        return $amount;
    }

    public function getStatus()
    {
        $status = 'overdue';
        if ($this->due_on > Carbon::today()->format('Y-m-d'))
            $status = 'outstanding';

        return $status;
    }

}
