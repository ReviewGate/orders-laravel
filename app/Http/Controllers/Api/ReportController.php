<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RevenueReportRequest;
use App\Services\RevenueReport;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    private const DEFAULT_PER_PAGE = 20;

    private const MAX_PER_PAGE = 100;

    public function revenue(RevenueReportRequest $request, RevenueReport $report): JsonResponse
    {
        $perPage = min((int) $request->integer('per_page', self::DEFAULT_PER_PAGE), self::MAX_PER_PAGE);

        return response()->json(
            $report->handle($request->string('from')->toString(), $request->string('to')->toString(), $perPage)
        );
    }
}
