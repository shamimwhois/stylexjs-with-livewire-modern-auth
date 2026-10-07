# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2026-10-08

### Added

- Initial application built on Laravel 13 (PHP 8.5), Livewire 3, and Laravel Fortify (`3fbb1b8`).
- Full authentication suite: registration, login with throttling, email verification, password reset, profile/password updates.
- Two-factor authentication (TOTP), passkeys (WebAuthn), one-time passwords, and magic link login.
- Password breach (pwned password) checks.
- Social login via GitHub, Google, Facebook, and Apple using Laravel Socialite.
- Role/permission-based access control with admin panel routes (`/admin/users`, `/admin/roles`, `/admin/permissions`).
- IP address whitelisting and authentication audit logging.
- Domain features: licence tiers, addons, feature requests, seller/marketplace dashboard, changelog records.
- StyleX styling pipeline with Vite 8 and a StyleX class map generator (`npm run stylex:map`).
- Pest test suite covering auth flows, RBAC, whitelisting, and seeders.
- Project tooling: `composer setup`, `composer dev`, `composer test` scripts and hardened `.gitignore`.

[Unreleased]: https://github.com/shamimwhois/stylexjs-with-livewire-modern-auth/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/shamimwhois/stylexjs-with-livewire-modern-auth/releases/tag/v1.0.0
