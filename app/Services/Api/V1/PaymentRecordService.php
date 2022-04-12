<?php


namespace App\Services\Api\V1;

use App\Models\PaymentRecord;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentRecordService
{
    public function getAll()
    {
        return PaymentRecord::paginate(10);
    }

    public function create(Request $request, Transaction $transaction) {
        return PaymentRecord::create($request->merge(['transaction_id' => $transaction->id])->all());
    }

}
