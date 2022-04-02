<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ReportRequest;
use App\Services\Api\V1\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function monthlyReport(ReportRequest  $reportRequest)
    {
        $report = $this->reportService->monthlyReport($reportRequest);

        return response()->success($report);
    }
}
