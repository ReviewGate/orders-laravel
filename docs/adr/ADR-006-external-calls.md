# ADR-006. External calls: a timeout and context in the log

## Context
`Http::post(...)` without `->timeout()` holds a worker indefinitely while the external service
hangs. A swallowed exception without context leaves nothing to analyse the incident with.

## Decision
- Calls to external systems run with an explicit `->timeout()` (and preferably `->connectTimeout()`).
- When an external call fails or a transaction is rolled back, the log gets the order id and the reason.

## Consequences
A hung service no longer eats the worker pool; the log shows what failed and for which order.

Checked automatically: the rules `timeouts-on-external-calls`, `log-context-on-failure`.
