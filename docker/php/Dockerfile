# 最新の安定版 PHP 8.3 を使用
FROM php:8.3-fpm

RUN apt-get update && apt-get install -y nginx zlib1g-dev libzip-dev mariadb-client vim \
  && docker-php-ext-install zip pdo_mysql

# 設定ファイルをコピー
COPY php.ini /usr/local/etc/php/

RUN apt-get update \
  && apt-get install -y \
    zlib1g-dev \
    libzip-dev \
    mariadb-client \
    vim \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) zip pdo_mysql gd

# Composerの導入
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

RUN composer global require "laravel/installer"

# Node.js 20.x (LTS) のインストール
# NodeSourceのリポジトリ設定を最新の推奨形式に変更
RUN apt-get update && apt-get install -y ca-certificates curl gnupg \
  && mkdir -p /etc/apt/keyrings \
  && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
  && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
  && apt-get update && apt-get install nodejs -y

# npmのアップデート（Vue CLIは最近はVite移行が推奨されるため、必要に応じて）
RUN npm install -g npm@latest

WORKDIR /var/www

RUN echo "file_uploads = On\n" \
         "memory_limit = 512M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini