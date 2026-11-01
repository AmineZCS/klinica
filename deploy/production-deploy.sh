#!/usr/bin/env bash
# Production deploy, run on the server. The CI SSH key is pinned to this script
# (forced command in authorized_keys), so a leaked key can only trigger a deploy,
# not open a shell.
set -euo pipefail
cd /var/www/klinica

compose() { docker compose -f compose.prod.yaml "$@"; }

# Keep the running image so a failed release can roll back to it.
docker image tag klinica-app:latest klinica-app:previous 2>/dev/null || true

git fetch origin main
git reset --hard origin/main

compose build
compose up -d --wait db redis

# Migrate before the new app serves traffic. Migrations must be
# backward-compatible: rollback restores the previous image, not the schema.
compose run --rm --no-deps --entrypoint php web artisan migrate --force
compose run --rm --no-deps --entrypoint php web artisan tenants:migrate --force

# Swap in the new containers. Brief downtime here — not zero-downtime.
compose up -d --wait

# The storage volume can predate the non-root container, so fix ownership as
# root before caching (view compilation and the app write here).
compose exec -u root -T web mkdir -p \
  /app/storage/app/public /app/storage/app/private \
  /app/storage/app/livewire-tmp /app/storage/framework/cache \
  /app/storage/framework/sessions /app/storage/framework/views \
  /app/storage/logs
compose exec -u root -T web chown -R www-data:www-data /app/storage /app/bootstrap/cache
compose exec -T web php artisan storage:link --force

# No view:cache — Filament resolves its blade components at panel boot, which
# view:cache can't do; filament:optimize covers Filament instead.
compose exec -T web php artisan config:cache
compose exec -T web php artisan event:cache
compose exec -T web php artisan route:cache
compose exec -T web php artisan filament:optimize
compose exec -T web php artisan octane:reload

smoke_ok() {
  compose exec -T web php artisan migrate:status > /dev/null 2>&1 \
    && compose exec -T web curl -fsS http://localhost:8000/up > /dev/null
}

if ! smoke_ok; then
  echo "Smoke test failed after migration."
  if docker image inspect klinica-app:previous > /dev/null 2>&1; then
    echo "Rolling back to the previous image (schema NOT reverted)."
    docker image tag klinica-app:previous klinica-app:latest
    compose up -d --wait
  else
    echo "No previous image to roll back to (first deploy)."
  fi
  exit 1
fi

docker image prune -f
