# StyleX with Livewire — Modern Auth

A Laravel 13 application built with Livewire 3, Laravel Fortify, and StyleX, focused on a complete, production-grade authentication suite backed by role-based access control and an audit trail.

## Highlights

**Authentication (Laravel Fortify)**

- Email/password registration and login with login throttling
- Two-factor authentication (TOTP) with QR code and confirmation flow
- Passkeys (WebAuthn) with password confirmation
- One-time password (OTP), magic link, and email verification
- Password reset and profile/password updates
- Password breach checks (pwned password detection)
- Social login via GitHub, Google, Facebook, and Apple (Laravel Socialite)
- IP address whitelisting and per-authentication audit logging

**Authorization**

- Role and permission management (admin panel at `/admin`)
- Gated routes: `/dashboard` (verified users), `/seller` (permission-gated), `/admin/*` (role + IP whitelist)

**Domain**

- Licence tiers, addons, and feature requests
- Seller/marketplace dashboard with country/address data
- Changelog records surfaced in the admin

## Tech Stack

| Layer | Tools |
| --- | --- |
| Framework | Laravel 13 (PHP 8.5) |
| UI | Livewire 3, Blade, Lucide icons (`mallardduck/blade-lucide-icons`) |
| Styling | StyleX (`@stylexjs/stylex`) via `@stylexjs/unplugin`, Vite 8 |
| Auth | Laravel Fortify, Laravel Sanctum, Laravel Socialite |
| Testing | Pest 5 |

## Requirements

- PHP 8.5+
- Composer
- Node.js 20+ and npm
- SQLite (default; any Laravel-supported database works)

## Quick Start

```bash
composer setup
```

This installs PHP dependencies, creates `.env`, generates an app key, runs migrations, and builds the frontend.

Then start the dev server, queue worker, and Vite together:

```bash
composer dev
```

The app is served by [Laravel Herd](https://laravel.com/docs/herd) at `https://whalelicense.test`; `php artisan serve` also works via `composer dev`.

### Manual Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
```

## Testing

```bash
composer test          # or: php artisan test --compact
```

The suite covers authentication flows (2FA, OTP, magic link, social, breach checks), RBAC, IP whitelisting, and database seeders.

## Project Structure

```
app/
├── Actions/Fortify/     # Fortify action overrides (login, 2FA, passkeys, …)
├── Http/Controllers/     # Web and API controllers
├── Livewire/             # Livewire components
├── Models/               # Eloquent models (licences, tiers, addons, sellers, …)
├── Providers/            # Service providers (incl. Apple Socialite provider)
config/fortify.php        # Feature flags for the auth suite
routes/web.php            # Routes and middleware (auth, verified, role, whitelist)
tests/                    # Pest feature and unit tests
```

## Security

If you discover a security vulnerability, please report it privately to the maintainers rather than opening a public issue.

## License

MIT — see repository for details. Laravel framework code is released under the [MIT license](https://opensource.org/licenses/MIT).
