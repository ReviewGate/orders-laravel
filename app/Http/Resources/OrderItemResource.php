<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'sku' => $this->sku,
            'title' => $this->title,
            'quantity' => $this->quantity,
            'unit_price_cents' => $this->unit_price_cents,
        ];
    }
}
