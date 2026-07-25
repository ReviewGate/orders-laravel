# orders-laravel — the order service

The order backend in PHP: order intake, statuses, reports. Laravel 11 + Eloquent, PostgreSQL.

## Development

```bash
composer install
php artisan migrate
php artisan serve
```

## Layout

```
app/
├── Models/            Order (the status constants, changeStatus, money in cents) · OrderItem
├── Services/          the business logic (ADR-001)
├── Http/
│   ├── Controllers/   thin API controllers
│   ├── Requests/      FormRequest input validation (ADR-004)
│   └── Resources/     the API Resource — an explicit response (ADR-007)
└── Clients/           the billing client — Http::timeout (ADR-006)
database/migrations/   the migrations (ADR-008)
```

The team's conventions live in [`docs/adr`](docs/adr). Review is configured in
[`.reviewgate/config.yml`](.reviewgate/config.yml). Money is whole cents (ADR-003).
