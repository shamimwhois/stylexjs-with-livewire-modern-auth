---
paths:
  - tests/Feature/**
---

# Feature

## Socialite fake signature: string driver, not array
Socialite v5 fake API is Socialite::fake('driver', $user) (string driver + user), NOT the older Socialite::fake([...]) array form. Build the fake user as `(new \Laravel\Socialite\Two\User)->map([...])` — never Socialite::driver(...)->userFromToken() in tests, that hits the live provider API.

## Git-Bash mangles SESSION_PATH — pin it in phpunit.xml
On this machine tests run through Git Bash, which rewrites the env value `SESSION_PATH=/` to `C:/Program Files/Git/` at process spawn. Dotenv reads `$_SERVER` FIRST (ServerConstAdapter), and PHPUnit `<env>` writes only `$_ENV`/putenv — never `$_SERVER` — so the mangled value wins and every page-render test 500s with `The cookie path "C:/Program Files/Git/" contains invalid characters`. Fixes already in phpunit.xml: `<env name="SESSION_PATH" value="/" force="true"/>` PLUS `<server name="SESSION_PATH" value="/"/>`. Both lines are load-bearing; if render tests start 500ing with the cookie-path error, check these first. Running pest from cmd.exe/PowerShell does not need this.

## Models use PHP attributes, not $fillable arrays
This app declares fillable/hidden via PHP 8 attributes (#[Fillable([...]), #[Hidden([...])] from Illuminate\Database\Eloquent\Attributes) on the model class — see User and OtpCode. A plain `protected $fillable = [...]` array is NOT the convention here; new models must use the attribute or mass assignment throws MassAssignmentException in tests.
