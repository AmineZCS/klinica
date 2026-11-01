# ==============================================================================
# STAGE 1: PHP Dependency Builder
# ==============================================================================
# Pin an explicit PHP patch release (>= 8.5.12) so builds don't silently drift
# and to guarantee the PHP2Shell fixes (CVE-2026-109618/109619) are present.
FROM dunglas/frankenphp:php8.5.12 AS base
WORKDIR /app

RUN apt-get update && apt-get install -y git unzip \
    && curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o /usr/local/bin/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions pcntl pdo_mysql zip opcache redis intl exif gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# ==============================================================================
# STAGE 2: Node.js Asset Compiler
# ==============================================================================
FROM node:20-alpine AS asset-builder
WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci

COPY --from=base /app/vendor /app/vendor

COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build

# ==============================================================================
# STAGE 3: Production Runtime
# ==============================================================================
FROM dunglas/frankenphp:php8.5.12 AS runner
WORKDIR /app

# Explicit curl install for healthcheck + php.ini tuning in one layer
RUN apt-get update && apt-get install -y curl \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' "$PHP_INI_DIR/php.ini" \
    # Mitigate CVE-2026-115560 (unpatched session-upload-progress RCE); the app
    # does not rely on PHP's native upload-progress tracking.
    && printf '\nsession.upload_progress.enabled = Off\n' >> "$PHP_INI_DIR/php.ini" \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o /usr/local/bin/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions pcntl pdo_mysql zip opcache redis intl exif gd \
    && rm -rf /var/lib/apt/lists/* && rm /usr/local/bin/install-php-extensions

COPY --from=base /app/vendor /app/vendor
COPY --from=asset-builder /app/public/build /app/public/build
COPY . .

# Ensure runtime dirs exist (some excluded by .dockerignore), discover packages
# skipped by --no-scripts, and link storage.
RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions \
        storage/framework/views storage/logs bootstrap/cache /data /config \
    && php artisan package:discover --ansi \
    && php artisan storage:link \
    && chown -R www-data:www-data /app /data /config

# Drop privileges: run Octane/FrankenPHP — and the worker/scheduler that reuse
# this image — as the unprivileged www-data user instead of root. /data and
# /config are FrankenPHP's runtime dirs and must be writable by that user.
USER www-data

EXPOSE 8000
ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
