---
paths:
  - 'app/Services/Auth/Breach/**'
---

# Breach

## Breach checking lives in App\Services\Auth\Breach
Data-breach checking lives in the App\Services\Auth\Breach domain: BreachService (entry point: check/isPwned), PwnedClient (HIBP HTTP + per-prefix cache + fail-open from config/pwned.php), BreachResult DTO, RiskLevel enum (fromCount thresholds 1/100/1000/10000). Api\AuthController only validates and returns the result through App\Http\Resources\BreachCheckResource. This is warning-only by design — do not gate registration on breach status.

## Breach API response shape is defined once, backend-first
The endpoint response (pwned, count, risk_level, label, message) is the single canonical contract, serialized by BreachResult::toArray() and exposed via BreachCheckResource. All human-readable texts (message via lang/en/auth.php, label, risk labels) are computed on the backend. The register wizard JS stores the whole response as one `breach` object and renders backend fields (breach.message, breach.label) via Alpine getters — it must NOT re-derive status texts or client-side risk thresholds. Keep the frontend free of duplicate breach semantics.
