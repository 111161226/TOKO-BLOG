FROM php:7.3-fpm
COPY php.ini /usr/local/etc/php/

RUN apt-get update \
  && apt-get install -y zlib1g-dev libzip-dev mariadb-client vim \
  && docker-php-ext-install zip pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

ENV COMPOSER_HOME /composer

ENV PATH $PATH:/composer/vendor/bin

RUN composer global require "laravel/installer"

RUN curl -SL https://deb.nodesource.com/setup_20.x | bash
RUN apt-get install -y nodejs && \
  npm install -g npm@latest && \
  npm install -g @vue/cli

WORKDIR /var/www

RUN echo "file_uploads = On\n" \
         "memory_limit = 512M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini