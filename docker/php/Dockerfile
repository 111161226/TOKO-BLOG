# 最新の安定版 PHP 8.3 を使用
FROM php:8.3-fpm

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

# 1. NginxとPHPの設定ファイルをコピー（ルートからのパスを指定）[cite: 2]
RUN rm /etc/nginx/sites-enabled/default
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

# 2. コンテナの作業ディレクトリを固定
WORKDIR /var/www/application

# 3. プロジェクトのソースコードをコピー[cite: 2]
COPY src/application/ . 

# Laravel Installer
RUN composer global require "laravel/installer"

# Node.js 20.x のインストール
RUN mkdir -p /etc/apt/keyrings \
  && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
  && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
  && apt-get update && apt-get install nodejs -y \
  && npm install -g npm@latest

# 4. ライブラリのインストールと権限設定[cite: 2]
RUN composer install --no-dev --optimize-autoloader \
  && chown -R www-data:www-data /var/www/application \
  && chmod -R 775 /var/www/application/storage /var/www/application/bootstrap/cache

# PHPのアップロード設定（追加分）
RUN echo "file_uploads = On\n" \
         "memory_limit = 512M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

# 起動
CMD php-fpm -D && nginx -g "daemon off;"