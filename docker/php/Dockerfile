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

# 設定ファイルのコピー [cite: 2]
RUN rm /etc/nginx/sites-enabled/default
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/php.ini

# --- ここからが重要 ---
ENV COMPOSER_HOME /composer
ENV PATH $PATH:/composer/vendor/bin

# 1. コンテナの作業ディレクトリを固定
WORKDIR /var/www/application

# 2. プロジェクトの「中身」をコンテナのルートにコピー
# こうすることで、コンテナの /var/www/application 直下に artisan や composer.json が来ます
COPY src/application/ . 
# Node.js 20.x (LTS) のインストール
# NodeSourceのリポジトリ設定を最新の推奨形式に変更
RUN apt-get update && apt-get install -y ca-certificates curl gnupg \
  && mkdir -p /etc/apt/keyrings \
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

# 3. ライブラリのインストール
# これにより /var/www/application/vendor が作成されます
RUN composer install --no-dev --optimize-autoloader

# 4. 権限設定
RUN chown -R www-data:www-data /var/www/application
RUN chmod -R 775 /var/www/application/storage /var/www/application/bootstrap/cache

# 起動
CMD php-fpm -D && nginx -g "daemon off;"
# 権限の調整
RUN chown -R www-data:www-data /var/www/application

# PHPのアップロード設定
RUN echo "file_uploads = On\n" \
         "memory_limit = 512M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini