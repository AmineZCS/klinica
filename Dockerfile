# Stage 1 — PHP dependencies
# Pinned patch release: avoids silent drift and includes the PHP2Shell fixes
# (CVE-2026-109618/109619).
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

# Stage 2 — frontend assets
FROM node:20-alpine AS asset-builder
WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci

COPY --from=base /app/vendor /app/vendor

COPY vite.config.js ./
COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build

# Stage 3 — production runtime
FROM dunglas/frankenphp:php8.5.12 AS runner
WORKDIR /app

RUN apt-get update && apt-get install -y curl \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && sed -i 's/variables_order = "GPCS"/variables_order = "EGPCS"/' "$PHP_INI_DIR/php.ini" \
    # Mitigates CVE-2026-115560 (session upload-progress); unused by the app.
    && printf '\nsession.upload_progress.enabled = Off\n' >> "$PHP_INI_DIR/php.ini" \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o /usr/local/bin/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions pcntl pdo_mysql zip opcache redis intl exif gd \
    && rm -rf /var/lib/apt/lists/* && rm /usr/local/bin/install-php-extensions

COPY --from=base /app/vendor /app/vendor
COPY --from=asset-builder /app/public/build /app/public/build
COPY . .

RUN mkdir -p storage/app/public storage/framework/cache storage/framework/sessions \
        storage/framework/views storage/logs bootstrap/cache /data /config \
    && php artisan package:discover --ansi \
    && php artisan storage:link \
    && chown -R www-data:www-data /app /data /config

# Run as non-root; /data and /config are FrankenPHP's writable runtime dirs.
USER www-data

EXPOSE 8000
ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8000"]
