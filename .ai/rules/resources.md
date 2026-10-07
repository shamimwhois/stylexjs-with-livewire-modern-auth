---
paths:
  - 'app/Http/Resources/**'
---

# Resources

## API Resources are flat (no data wrapper)
API Resources are intentionally UNWRAPPED (JsonResource::withoutWrapping() in AppServiceProvider::boot). The API returns flat JSON — do not add a `data` envelope; the register wizard JS reads breach fields directly off the top-level object.
