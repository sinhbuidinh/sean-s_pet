FROM php:8.1-fpm

# Import arguments
ARG VERSION

# Set environments
ENV VERSION ${VERSION}

# Set working directory
WORKDIR /var/www

# Add docker php extension repo
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
  install-php-extensions pdo_mysql mbstring exif pcntl bcmath gd

# Install dependencies
RUN apt-get update && apt-get install -y \
  build-essential \
  zip \
  unzip \
  curl \
  nginx \
  supervisor

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user for the app
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy code to /var/www
COPY --chown=www-data:www-data . /var/www

# Add sufficient folder permission
RUN chgrp -R www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R ug+rwx /var/www/storage /var/www/bootstrap/cache

# Copy configurations
RUN cp docker/supervisor.conf /etc/supervisord.conf
RUN cp docker/php.ini /usr/local/etc/php/conf.d/app.ini
RUN cp docker/www.conf /usr/local/etc/php-fpm.d/www.conf
RUN cp docker/nginx.conf /etc/nginx/sites-enabled/default

# PHP error log files
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

# Deployment steps
RUN chmod +x /var/www/docker/run.sh

EXPOSE 80
ENTRYPOINT ["bash", "/var/www/docker/run.sh"]
