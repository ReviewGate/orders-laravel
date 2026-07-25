<?php

declare(strict_types=1);

namespace App\Clients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/** Sends the finished report to an external BI dashboard where finance looks at it. */
class AnalyticsClient
{
    /** @param array<string, mixed> $report */
    public function send(array $report): void
    {
        try {
            Http::post(config('services.analytics.webhook_url'), $report);
        } catch (Throwable $e) {
            Log::error('failed to send the report to analytics: '.$e->getMessage());
        }
    }
}
