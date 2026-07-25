<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The response contents are stated explicitly. Returning the whole model (response()->json($order)) is not allowed:
 * internal fields (cost_price_cents, manager_note) must not leave the service (ADR-007).
 */
class OrderResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_email' => $this->customer_email,
            'status' => $this->status,
            'total_cents' => $this->total_cents,
            'currency' => $this->currency,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
