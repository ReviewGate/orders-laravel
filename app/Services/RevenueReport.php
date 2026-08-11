<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/** The revenue report for a period, broken down by order. */
class RevenueReport
{
    private const CURRENCY = 'USD';

    /** @return array<string, mixed> */
    public function handle(string $from, string $to, int $perPage): array
    {
        // with('items') kills the N+1, paginate() caps what a single request can pull.
        $orders = Order::query()
            ->with('items')
            ->where('status', Order::STATUS_PAID)
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('id')
            ->paginate($perPage);

        $lines = [];
        $totalCents = 0;

        foreach ($orders as $order) {
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
            'currency' => self::CURRENCY,
            'total_cents' => $totalCents,
            'orders' => $lines,
            'page' => $orders->currentPage(),
            'total_pages' => $orders->lastPage(),
        ];

        return $report;
    }
}
