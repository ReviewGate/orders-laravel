<?php

declare(strict_types=1);

namespace App\Clients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/** Sends the finished report to an external BI dashboard where finance looks at it. */
class AnalyticsClient
{
    /** A hung dashboard must not hold the worker (ADR-006). */
    private const CONNECT_TIMEOUT_SECONDS = 2;

    private const TIMEOUT_SECONDS = 5;

    /** @param array<string, mixed> $report */
    public function send(array $report): void
    {
        try {
            $response = Http::connectTimeout(self::CONNECT_TIMEOUT_SECONDS)
                ->timeout(self::TIMEOUT_SECONDS)
                ->post(config('services.analytics.webhook_url'), $report);

            if ($response->failed()) {
                Log::error(sprintf(
                    'analytics answered %d for the report %s-%s',
                    $response->status(),
                    $report['from'],
                    $report['to'],
                ));
            }
        } catch (Throwable $e) {
            Log::error(sprintf(
                'failed to send the report %s-%s to analytics: %s',
                $report['from'],
                $report['to'],
                $e->getMessage(),
            ));
        }
    }
}
