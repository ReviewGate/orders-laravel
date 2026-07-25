# ADR-007. The response is an API Resource with explicit fields

## Context
`response()->json($order)` returns the whole model, including the fields it gains tomorrow.
That is how a purchase price (`cost_price_cents`) once went out in an API response.

## Decision
- The response is assembled through an API Resource (`app/Http/Resources`) with an **explicit field list**.
- Returning a model or a collection directly is banned. Internal fields (`cost_price_cents`,
  `manager_note`, `cancellation_reason`) are not part of the resource.

## Consequences
The response contents are a conscious decision; a new internal field does not leak by itself.

Checked automatically: the rule `no-internal-fields-in-response`.
