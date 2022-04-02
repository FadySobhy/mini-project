<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PaymentRecordRequest;
use App\Services\Api\V1\PaymentRecordService;
use App\Services\Api\V1\TransactionService;
use Illuminate\Http\Request;

class PaymentRecordController extends Controller
{
    protected $paymentRecordService;
    protected $transactionService;

    public function __construct(PaymentRecordService $paymentRecordService, TransactionService $transactionService)
    {
        $this->paymentRecordService = $paymentRecordService;
        $this->transactionService   = $transactionService;
    }

    public function index()
    {
        $paymentRecords = $this->paymentRecordService->getAll();

        return response()->success($paymentRecords);
    }

    public function create(PaymentRecordRequest $paymentRecordRequest)
    {
        $transaction    = $this->transactionService->findById($paymentRecordRequest->transaction_id);
        $paymentRecordResponse  = $this->paymentRecordService->create($paymentRecordRequest, $transaction);

        return $paymentRecordResponse;
    }
}
