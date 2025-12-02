FROM php:8.1-apache

# ===== System packages + PHP extensions for Moodle =====
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libpq-dev \
    libxml2-dev \
    unzip \
    git \
    curl \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install \
       gd \
       intl \
       zip \
       pdo \
       pdo_pgsql \
       pgsql \
       soap \
       exif \
  && a2enmod rewrite \
  && rm -rf /var/lib/apt/lists/*

# ===== PHP settings tuned for Moodle =====
RUN echo "max_input_vars=5000"           >  /usr/local/etc/php/conf.d/moodle.ini \
 && echo "memory_limit=256M"            >> /usr/local/etc/php/conf.d/moodle.ini \
 && echo "upload_max_filesize=64M"      >> /usr/local/etc/php/conf.d/moodle.ini \
 && echo "post_max_size=64M"            >> /usr/local/etc/php/conf.d/moodle.ini \
 && echo "opcache.enable=1"             >> /usr/local/etc/php/conf.d/moodle.ini \
 && echo "opcache.validate_timestamps=1">> /usr/local/etc/php/conf.d/moodle.ini

# (Optional) explicitly set document root – we’ll still serve /moodle
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# ===== Copy Moodle source code =====
# On your host: moodle-koyeb/moodle/ should contain admin/, auth/, index.php, etc.
COPY moodle/ /var/www/html/moodle/

# ===== Copy env-driven config.php =====
# This is the file you pasted with env() helper.
COPY config.php /var/www/html/moodle/config.php

# ===== Data directory + permissions =====
RUN mkdir -p /var/moodledata \
 && chown -R www-data:www-data /var/moodledata /var/www/html

WORKDIR /var/www/html
EXPOSE 80
