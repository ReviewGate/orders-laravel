# ADR-002. Queries: paginate, eager loading, indexes

## Context
Eloquent loads relations lazily: touching `$order->items` in a loop over orders produces a query per
iteration (N+1). A list without `paginate()` exports the whole table one day.

## Decision
- Every list endpoint uses `paginate()` with an upper bound on `per_page`
  (`MAX_PER_PAGE`). `->get()`/`->all()` for lists that go out are banned.
- Touching a relation or running a query inside a loop is banned: relations are loaded through `with()`/`load()`,
  and a total over many rows is an aggregate (`sum` in SQL) rather than summation in PHP.
- A column that is filtered or sorted on has an index created by a migration.

## Consequences
Reports and lists no longer take the database down as the orders table grows.

Checked automatically: the rules `paginate-every-list`, `no-queries-in-loop`, `index-for-filtered-column`.
