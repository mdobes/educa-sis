FROM webdevops/php-nginx:8.1

ENV TZ=Europe/Prague

# Install Laravel framework system requirements (https://laravel.com/docs/8.x/deployment#optimizing-configuration-loading)
RUN apt-get update \
    && apt-get install -y \
    && apt-get autoremove -y \
    && apt-get install -y  \
    libgd-dev \
    curl \
    libzip-dev \
    zip \
    cron

RUN \
apt-get update && \
apt-get install libldap2-dev -y && \
rm -rf /var/lib/apt/lists/* && \
docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
docker-php-ext-install ldap

# Copy Composer binary from the Composer official Docker image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV WEB_DOCUMENT_ROOT /app/public
ENV APP_ENV production
WORKDIR /app
COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN php artisan key:generate --force
# Optimizing Configuration loading
RUN php artisan config:cache
# Optimizing Route loading
RUN php artisan route:cache
# Optimizing View loading
RUN php artisan view:cache

RUN php artisan migrate --force

RUN chown -R application:application .

