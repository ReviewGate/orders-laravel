<?php

declare(strict_types=1);

namespace App\Clients;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * The external billing client. The call carries an explicit timeout: without it slow billing holds
 * workers and drags the process down with it (ADR-006).
 */
class BillingClient
{
    private const CONNECT_TIMEOUT_SECONDS = 2;
    private const TIMEOUT_SECONDS = 5;

    public function charge(int $orderId, int $amountCents, string $idempotencyKey): string
    {
        $response = Http::timeout(self::TIMEOUT_SECONDS)
            ->connectTimeout(self::CONNECT_TIMEOUT_SECONDS)
            ->withHeaders(['idempotency-key' => $idempotencyKey])
            ->post(config('services.billing.base_url').'/v1/charges', [
                'order_id' => $orderId,
                'amount_cents' => $amountCents,
            ]);

        if ($response->failed()) {
            Log::error("Order {$orderId}: the billing charge did not go through", [
                'status' => $response->status(),
            ]);

            throw new RuntimeException("billing answered {$response->status()}");
        }

        return (string) $response->json('transaction_id');
    }
}
