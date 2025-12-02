FROM php:8.1-apache

# ===== System packages + PHP extensions for Moodle (MariaDB) =====
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    unzip \
    git \
    curl \
    mariadb-client \
 && docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
      gd \
      intl \
      zip \
      mysqli \
      pdo \
      pdo_mysql \
      soap \
      exif \
      opcache \
 && a2enmod rewrite \
 && rm -rf /var/lib/apt/lists/*

# ===== PHP settings tuned for Moodle =====
RUN echo "max_input_vars=5000"              >  /usr/local/etc/php/conf.d/moodle.ini \
 && echo "memory_limit=512M"               >> /usr/local/etc/php/conf.d/moodle.ini \
 && echo "upload_max_filesize=64M"         >> /usr/local/etc/php/conf.d/moodle.ini \
 && echo "post_max_size=64M"               >> /usr/local/etc/php/conf.d/moodle.ini \
 && echo "max_execution_time=300"          >> /usr/local/etc/php/conf.d/moodle.ini \
 && echo "opcache.enable=1"                >> /usr/local/etc/php/conf.d/moodle.ini \
 && echo "opcache.validate_timestamps=1"   >> /usr/local/etc/php/conf.d/moodle.ini

# Optional: allow .htaccess in the web root (Moodle needs this)
RUN printf "<Directory /var/www/html>\n    AllowOverride All\n</Directory>\n" \
      > /etc/apache2/conf-available/moodle.conf \
 && a2enconf moodle

# (Optional) explicit document root – we still serve /moodle subfolder
ENV APACHE_DOCUMENT_ROOT=/var/www/html

# ===== Copy Moodle source code =====
# Your repo should have moodle/ containing admin/, auth/, index.php, etc.
COPY moodle/ /var/www/html/moodle/

# ===== Copy env-driven config.php =====
COPY config.php /var/www/html/moodle/config.php

# ===== Data directory + permissions =====
RUN mkdir -p /var/moodledata \
 && chown -R www-data:www-data /var/moodledata /var/www/html

WORKDIR /var/www/html
EXPOSE 80
