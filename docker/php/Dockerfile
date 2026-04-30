# 最新の安定版 PHP 8.3 を使用
FROM php:8.3-fpm

# パッケージインストールを一回にまとめてビルド時間を短縮
RUN apt-get update && apt-get install -y \
    nginx \
    zlib1g-dev \
    libzip-dev \
    mariadb-client \
    vim \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    ca-certificates \
    curl \
    gnupg \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) zip pdo_mysql gd

# Composerの導入
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

# Laravel Installer
RUN composer global require "laravel/installer"

# 設定ファイルをコピー（画像に基づき、Dockerfileと同じ階層にある php.ini を参照）
COPY php.ini /usr/local/etc/php/

# Node.js 20.x のインストール (最新の推奨形式)
RUN mkdir -p /etc/apt/keyrings \
  && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
  && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
  && apt-get update && apt-get install nodejs -y \
  && npm install -g npm@latest

# 作業ディレクトリの設定
WORKDIR /var/www/application

# 権限の調整
RUN chown -R www-data:www-data /var/www/application

# PHPのアップロード設定
RUN echo "file_uploads = On\n" \
         "memory_limit = 512M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini