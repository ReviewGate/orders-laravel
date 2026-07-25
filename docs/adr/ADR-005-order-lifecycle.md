# ADR-005. The order status changes through a domain method

## Context
`$order->update(['status' => 'paid'])` anywhere in the code moves an order from cancelled to
paid, bypassing the check that the transition is allowed.

## Decision
- The status changes only through a domain method of the model (`changeStatus`, `markPaid`), which checks
  the `ALLOWED_TRANSITIONS` matrix and throws when the transition is not allowed.
- `$order->status = ...` and `update(['status' => ...])` outside the model are banned.

## Consequences
An invalid transition is impossible; the order lifecycle lives in one place.

Checked automatically: the rule `status-changes-via-domain-method`.
