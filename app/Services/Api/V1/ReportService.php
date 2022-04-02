<?php


namespace App\Services\Api\V1;

use Illuminate\Http\Request;

class ReportService
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function monthlyReport(Request $request)
    {
        return $this->transactionService->getMonthlyTransactions($request->start_date, $request->end_date);
    }

}
