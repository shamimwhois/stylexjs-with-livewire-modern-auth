---
paths:
  - 'database/migrations/**'
---

# Migrations

## Chain unique() before constrained() on foreignId columns
On foreignId() columns, column modifiers like ->unique() must come BEFORE ->constrained(). constrained() returns a ForeignKeyDefinition, so chaining ->unique() after it is silently ignored (compiler drops it) and no index is created. Prefer ->foreignId(...)->unique()->constrained()->cascadeOnDelete().
