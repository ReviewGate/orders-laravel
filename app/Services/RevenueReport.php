<?php

declare(strict_types=1);

namespace App\Services;

use App\Clients\AnalyticsClient;
use App\Models\Order;

/** The revenue report for a period, broken down by order. */
class RevenueReport
{
    public function __construct(private readonly AnalyticsClient $analytics)
    {
    }

    /** @return array<string, mixed> */
    public function handle(string $from, string $to): array
    {
        $orders = Order::query()
            ->where('status', 'paid')
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id')
            ->get();

        $lines = [];
        $totalCents = 0;

        foreach ($orders as $order) {
            // items are fetched per order
            $orderTotal = 0;
            foreach ($order->items as $item) {
                $orderTotal += $item->totalCents();
            }

            $totalCents += $orderTotal;
            $lines[] = [
                'order_id' => $order->id,
                'status' => $order->status,
                'total_cents' => $orderTotal,
            ];
        }

        $report = [
            'from' => $from,
            'to' => $to,
            'currency' => 'USD',
            'total_cents' => $totalCents,
            'orders' => $lines,
        ];

        $this->analytics->send($report);

        return $report;
    }
}
