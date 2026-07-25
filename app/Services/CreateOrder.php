<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

/** A service: the business logic lives here and the controller stays thin (ADR-001). */
class CreateOrder
{
    /** @param array<string, mixed> $data */
    public function handle(array $data): Order
    {
        return DB::transaction(function () use ($data): Order {
            $totalCents = array_sum(array_map(
                static fn (array $item): int => $item['unit_price_cents'] * $item['quantity'],
                $data['items']
            ));

            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'customer_email' => $data['customer_email'],
                'total_cents' => $totalCents,
                'currency' => Order::CURRENCY_USD,
            ]);

            $order->items()->createMany($data['items']);

            return $order->load('items');
        });
    }
}
