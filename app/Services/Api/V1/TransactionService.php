<?php


namespace App\Services\Api\V1;

use App\Models\PaymentRecord;
use App\Support\Api\V1\TransactionSupport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    protected $paymentRecordService;

    public function __construct(PaymentRecordService $paymentRecordService)
    {
        $this->paymentRecordService = $paymentRecordService;
    }

    public function findById($id)
    {
        return Transaction::where('id', $id)->first();
    }

    public function getAll()
    {
        $transactions = Transaction::with(['payer:id,name', 'category:id,name', 'subcategory:id,name'])
            ->withSum('paymentRecords', 'amount')
            ->paginate(10);
        $this->setStatusToTransactions($transactions);

        return $transactions;
    }

    public function myTransactions()
    {
        $transactions = Transaction::where('payer', auth()->user()->id)
            ->with(['category:id,name', 'subcategory:id,name'])
            ->withSum('paymentRecords', 'amount')
            ->paginate(10);
        $this->setStatusToTransactions($transactions);

        return $transactions;
    }

    private function setStatusToTransactions($transaction) {
        $transaction->transform(function ($item, $key) {
            $status     = '';
            $todayDate  = date('Y-m-d');
            $remainingAmount = $item->amount - $item->payment_records_sum_amount;

            if ($remainingAmount == 0)
                $status = 'paid';
            elseif ($item->due_on > $todayDate  && $remainingAmount > 0)
                $status = 'outstanding';
            elseif ($item->due_on <= $todayDate && $remainingAmount > 0)
                $status = 'overdue';

            $item->status = $status;

            return $item;
        });
    }

    public function create(Request $request) {
        DB::beginTransaction();
        try {
            $transactionObject = TransactionSupport::fromRequest($request);

            $totalAmount = $transactionObject->getTotalAmount();
            $transaction = Transaction::create([
                'amount'            => $totalAmount,
                'due_on'            => $transactionObject->due_on,
                'vat'               => $transactionObject->vat,
                'vat_included'      => $transactionObject->vat_included,
                'payer'             => $request->get('payer'),
                'category_id'       => $request->get('category_id'),
                'subcategory_id'    => $request->get('subcategory_id')
            ]);

            if (isset($request->payment) && !empty($request->payment)){
                $paymentRequest = new Request($request->get('payment')[0]);
                $paymentRecord = $this->paymentRecordService->create($paymentRequest, $transaction);
            }

            DB::commit();
            return response()->success($transaction);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->error(['message' => 'Transaction failed, please try again later.'], 500);
        }

    }

    public function getMonthlyTransactions($startDate, $endDate) {
        $todayDate = date('Y-m-d');
        $totalPaymentAmountSubQuery = '(SELECT SUM(payment_records.amount) FROM payment_records WHERE payment_records.transaction_id = transactions.id GROUP BY payment_records.transaction_id)';

        return Transaction::query()
            ->where('due_on', '>=', $startDate)
            ->where('due_on', '<=', $endDate)
            ->groupByRaw('Year(due_on), Month(due_on)')
            ->select( array(
                DB::raw('Month(due_on) AS month'),
                DB::raw('Year(due_on) AS year'),
                DB::raw("SUM(IF(transactions.amount = ".$totalPaymentAmountSubQuery.", transactions.amount, 0)) as paid"),
                DB::raw("SUM(IF(due_on > '".$todayDate."', transactions.amount - ".$totalPaymentAmountSubQuery.", 0)) as outstanding"),
                DB::raw("SUM(IF(due_on <= '". $todayDate ."', transactions.amount - ".$totalPaymentAmountSubQuery.", 0)) as overdue"),
            ))
            ->get();
    }

}
