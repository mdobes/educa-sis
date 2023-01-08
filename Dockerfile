FROM php:8.2-apache

ENV TZ=Europe/Prague

RUN apt-get update \
    && apt-get install -y \
    && apt-get autoremove -y \
    && apt-get install -y  \
    libgd-dev \
    curl \
    libzip-dev \
    zip \
    cron

RUN docker-php-ext-install mysqli pdo pdo_mysql gd zip ctype iconv

RUN php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN a2enmod rewrite
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html/

RUN composer install --no-interaction --optimize-autoloader --no-dev
# Optimizing Configuration loading
RUN php artisan config:cache
# Optimizing Route loading
RUN php artisan route:cache
# Optimizing View loading
RUN php artisan view:cache

RUN php artisan migrate

EXPOSE 8050
