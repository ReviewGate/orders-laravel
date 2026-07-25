# ADR-003. Money — whole cents

## Context
`0.1 + 0.2 != 0.3`. Dollars in a float on order totals diverge from the till, and it is caught in
production, on a customer's receipt.

## Decision
- Money is stored as whole cents (`bigInteger`) and returned through the API as an integer.
- Float is banned for money at every level (model, resource, response). Converting to dollars happens on output.

## Consequences
Cents add up as integers, so there are no rounding errors.

Checked automatically: the rule `money-in-minor-units`.
