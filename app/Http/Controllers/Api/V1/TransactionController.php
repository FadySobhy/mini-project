<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\TransactionRequest;
use App\Services\Api\V1\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $transactions = $this->transactionService->getAll();

        return response()->success($transactions);
    }

    public function myTransactions()
    {
        $transactions = $this->transactionService->myTransactions();

        return response()->success($transactions);
    }

    public function create(TransactionRequest $transactionRequest)
    {
        $transactionResponse = $this->transactionService->create($transactionRequest);

        return $transactionResponse;
    }
}
