# ADR-004. Input validation and parameterised SQL

## Context
Direct access to `$request->all()` without validation accepts any garbage and opens up mass
assignment. User input inside an SQL string is an injection.

## Decision
- The request body is validated by a FormRequest (or `$request->validate()`). Query parameters
  that affect the selection (filter, sorting, period) are validated as well.
- User input is never concatenated into SQL (`DB::raw`, `whereRaw` with interpolation) — only
  bound parameters. This also covers `orderBy` taken from a query parameter.

## Consequences
Invalid input is cut off at the boundary with a clear 422; injections are impossible.

Checked automatically: the rules `validate-every-input`, `no-raw-sql-interpolation`.
