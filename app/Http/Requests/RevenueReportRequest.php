<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/** The report period. The query used to reach the service unchecked (ADR-004). */
class RevenueReportRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
