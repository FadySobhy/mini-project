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
        DB::beginTransaction();
        try {
            $paymentRecord = PaymentRecord::create($request->merge(['transaction_id' => $transaction->id])->all());
            TransactionService::updateTransaction($transaction, $paymentRecord);

            DB::commit();
            return response()->success($paymentRecord);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->error(['message' => 'Payment record failed, Please try again later.'], 500);
        }
    }

}
