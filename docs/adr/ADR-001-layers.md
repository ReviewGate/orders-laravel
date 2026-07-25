# ADR-001. Thin controllers, the logic in services

## Context
When business logic lives in a controller action it cannot be called from an artisan command or a
queue, and it can only be tested through HTTP.

## Decision
- The controller is responsible for HTTP only: parsing the request (FormRequest), calling the service, response codes.
- Business logic, work across several models and transactions live in services (`app/Services`).

## Consequences
The logic can be called from anywhere; a controller action reads in five seconds.

Checked automatically: the rule `controller-through-service`.
