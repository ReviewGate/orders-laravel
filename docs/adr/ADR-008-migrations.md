# ADR-008. A schema change ships with its migration in the same pull request

## Context
A query against a column the database does not have passes review and fails on deploy.

## Decision
- Every database schema change ships with a migration **in the same pull request**.
- The migration creates the indexes for new filters and sortings (see ADR-002).

## Consequences
Schema and code ship together; deploys stop failing on a mismatch.

Checked automatically: the rule `schema-change-needs-migration`.
