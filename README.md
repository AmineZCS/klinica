<h1 align="center">Klinica</h1>

<p align="center">
  <strong>Multi-tenant SaaS for medical clinics</strong> — subdomain-per-clinic, a
  customizable public landing page, online booking, and a full admin panel.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License: MIT">
  <img src="https://img.shields.io/badge/PHP-8.4+-777BB4?style=flat-square&logo=php&logoColor=white" alt="PHP 8.4+">
  <img src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white" alt="Laravel 13">
  <img src="https://img.shields.io/badge/Filament-5-FDAE4B?style=flat-square" alt="Filament 5">
  <img src="https://img.shields.io/badge/Livewire-4-FB70A9?style=flat-square&logo=livewire&logoColor=white" alt="Livewire 4">
  <img src="https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white" alt="Tailwind CSS 4">
  <img src="https://img.shields.io/badge/Octane-FrankenPHP-0E7FC0?style=flat-square" alt="Octane + FrankenPHP">
  <img src="https://img.shields.io/badge/Tested_with-Pest-5A67D8?style=flat-square" alt="Tested with Pest">
</p>

---

Billing, trials, and plan restrictions are handled end-to-end with live payments.
Built as an indie project to see how fast a complete, production-deployable SaaS
could be shipped — it runs in production on a single cloud VM (Docker Compose)
behind FrankenPHP + Octane.

## Features

- **Multi-tenancy** — subdomain-per-tenant with isolated databases ([`stancl/tenancy`](https://tenancyforlaravel.com)).
- **Billing & subscriptions** — Paddle integration with trials, renewable subscriptions,
  plan swaps, cancellations, and webhook-driven state sync.
- **Plan restrictions** — Starter vs Pro limits (patients, employees, monthly bookings,
  storage, email reminders) enforced via middleware.
- **Public booking** — each clinic exposes a branded landing page and a self-service
  appointment booking flow.
- **Customizable landing page** — tenants edit branding, colors, contact info, and
  copy from their settings panel, with a **live preview** of the public site before saving.
- **Two Filament panels** — a central super-admin panel (tenants, subscriptions,
  analytics) and a per-tenant clinic panel (bookings, patients, services, staff, calendar).
- **Roles & permissions** — per-tenant RBAC ([`spatie/laravel-permission`](https://spatie.be/docs/laravel-permission)).
- **Multi-language** — English, French, German, Spanish.
- **SEO** — per-tenant sitemaps, `llms.txt`, IndexNow submission, noindex for unready tenants.
- **Audit log** — tenant activity tracking ([`spatie/laravel-activitylog`](https://spatie.be/docs/laravel-activitylog)).
- **GDPR** — per-tenant data export.

## Stack

- PHP 8.4+ / Laravel 13
- Filament 5 (admin + tenant panels)
- Livewire 4 + Alpine.js + Tailwind CSS 4
- MySQL 8, Redis
- FrankenPHP + Laravel Octane (production runtime)
- Laravel Sail (local development)
- Pest 4 (testing)

## Local development

Requires Docker. The project uses [Laravel Sail](https://laravel.com/docs/sail).

```bash
git clone <repo-url> klinica && cd klinica

cp .env.example .env
composer install

vendor/bin/sail up -d
vendor/bin/sail artisan key:generate
vendor/bin/sail artisan migrate
vendor/bin/sail npm install
vendor/bin/sail npm run dev
```

Multi-tenancy is resolved by subdomain, so point a wildcard host at the app for
local tenant testing (e.g. `*.klinica.test` → `127.0.0.1`).

### Configuration

Payment and SEO integrations read from `config/services.php`. Set these in `.env`:

```dotenv
PADDLE_API_KEY=
PADDLE_WEBHOOK_SECRET=
PADDLE_SANDBOX=true
PADDLE_PRICE_STARTER=
PADDLE_PRICE_PRO=
INDEXNOW_KEY=
```

## Testing

```bash
vendor/bin/sail artisan test
```

## Deployment

Production is a multi-stage Docker build (`Dockerfile`) running FrankenPHP with
Laravel Octane. `compose.prod.yaml` defines the production stack. A GitHub Actions
workflow (`.github/workflows/deploy.yml`) builds and deploys to the server.

## License

MIT — see [LICENSE](LICENSE).
