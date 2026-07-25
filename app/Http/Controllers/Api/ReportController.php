<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RevenueReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function revenue(Request $request, RevenueReport $report): JsonResponse
    {
        return response()->json(
            $report->handle($request->query('from'), $request->query('to'))
        );
    }
}
