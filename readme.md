# Sparekey.club

Share spare game keys with friends. Create groups, add leftover keys from bundles, and let friends claim the games they want.

## Stack

- PHP 8.3 / Laravel 12 / Laravel Sail
- Inertia.js v2 + Vue 3 + TypeScript + Tailwind CSS 3
- Filament 5 (admin) / Livewire 4
- Spatie Laravel Data + TypeScript Transformer
- Laravel Wayfinder (typed routes/actions)
- Pest 3

## About

Sparekey.club is a group-scoped key sharing app with karma, claim cooldowns, Discord webhook notifications, and social login providers.

## Installation

```bash
git clone https://github.com/andy3471/sparekey.club.git
cd sparekey.club

composer install
cp .env.example .env
php artisan key:generate

# If host Redis already uses 6379, set FORWARD_REDIS_PORT=6380 in .env
vendor/bin/sail up -d

vendor/bin/sail npm install
vendor/bin/sail npm run build
vendor/bin/sail artisan migrate

# Encrypt existing plaintext key codes / Discord webhooks after deploy
vendor/bin/sail artisan security:encrypt-sensitive-attributes
```

## Development checks

```bash
vendor/bin/sail bin pest --compact
vendor/bin/sail npm run type-check
vendor/bin/sail npm run lint
vendor/bin/sail bin phpstan analyse
vendor/bin/sail composer audit
vendor/bin/sail npm audit --omit=dev
```

Production npm dependencies currently audit clean. Remaining `npm audit` findings without `--omit=dev` are transitive/dev-tooling issues (ESLint/PostCSS/Tailwind typography) that need major bumps outside this project's version lock.

Generate frontend types/routes after backend DTO or route changes:

```bash
vendor/bin/sail artisan typescript:transform
vendor/bin/sail artisan wayfinder:generate
```

### Browser tests

`pestphp/pest-plugin-browser` requires Pest 4 and PHP 8.4+. This project stays on Pest 3 / PHP 8.3, so end-to-end coverage lives in Feature smoke tests (`tests/Feature/SmokeFlowTest.php`) instead of a Pest browser suite for now.

## Contributing

Pull requests are welcome. Use Laravel Sail for local development and keep Feature tests green for the area you change.

## Support

- [Ko-fi](https://ko-fi.com/andy3471)
- [GitHub](https://github.com/andy3471/sparekey.club)
