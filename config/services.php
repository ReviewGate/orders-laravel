<?php

declare(strict_types=1);

return [
    'billing' => [
        'base_url' => env('BILLING_BASE_URL', 'http://localhost:8081'),
    ],

    'analytics' => [
        'webhook_url' => env('ANALYTICS_WEBHOOK_URL', 'http://localhost:8082/revenue'),
    ],
];
